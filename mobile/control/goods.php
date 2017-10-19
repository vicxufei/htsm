<?php
/**
 * 商品
 *
 *
 *
 *by wansyb QQ群：111731672
 *你正在使用的是由网店 运 维提供S2.0系统！保障你的网络安全！ 购买授权请前往shopnc
 */



defined('ByShopWWI') or exit('Access Invalid!');
class goodsControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');
        $model_search = Model('search');

        //查询条件
        $condition = array();

        // ==== 暂时不显示定金预售商品，手机端未做。  ====
        if(!empty($_GET['cate_id']) && intval($_GET['cate_id']) > 0) {
            $condition['gc_id'] = $_GET['cate_id'];
            $default_classid = intval($_GET['cate_id']);
        } elseif (!empty($_GET['keyword'])) {
            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
			
			if (cookie('his_sh') == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', cookie('his_sh'));
            }
            if (strlen($_GET['keyword']) <= 30 && !in_array($_GET['keyword'],$his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setNcCookie('his_sh', implode('~', $his_sh_list),2592000); //添加历史纪录
            //从TAG中查找分类
            $goods_class_array = $model_search->getTagCategory($_GET['keyword']);
            //取出第一个分类作为默认分类，从而显示相应的属性和品牌
            $default_classid = $goods_class_array[0];

        } elseif (!empty($_GET['barcode'])) {
            $condition['goods_barcode'] = $_GET['barcode'];
        } elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }

        //if (!empty($_GET['price_from']) && intval($_GET['price_from'] > 0)) {
        //    $condition['goods_price'][] = array('egt',intval($_GET['price_from']));
        //}
        //if (!empty($_GET['price_to']) && intval($_GET['price_to'] > 0)) {
        //    $condition['goods_price'][] = array('elt',intval($_GET['price_to']));
        //}
		//if (intval($_GET['area_id']) > 0) {
		//	$condition['areaid_1'] = intval($_GET['area_id']);
		//}
		

		////团购
		//if ($_GET['groupbuy'] == 1) {
		//	$condition['goods_promotion_type'][] = 1;
		//}
		//限时折扣
		if ($_GET['xianshi'] == 1) {
			$condition['goods_promotion_type'][] = 2;
		}
		////虚拟
		//if ($_GET['virtual'] == 1) {
		//	$condition['is_virtual'] = 2;
		//}
        //所需字段
        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,goods_image,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_salenum,evaluation_count,goods_edittime";

        // 添加3个状态字段
       //$fieldstr .= ',is_virtual,is_own_shop';

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);
        if(empty($_GET['keyword']) || strlen($_GET['keyword']) > 3){
            //全文搜索搜索参数
            $indexer_searcharr = $_GET;

            //if ($_GET['own_shop'] == 1) {
            //    $indexer_searcharr['type'] = 1;
            //}
            //$indexer_searcharr['price_from'] = $price_from;
            //$indexer_searcharr['price_to'] = $price_to;

            //优先从全文索引库里查找
            list($goods_list,$indexer_count) = $model_search->indexerSearch($indexer_searcharr,$this->page);
        }

        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $model_search->getAttr($indexer_searcharr, $default_classid);
        if (!empty($goods_list)) {
            $goods_list = array_values($goods_list);
            pagecmd('setEachNum',$this->page);
            pagecmd('setTotalNum',$indexer_count);
        } else {
            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
        }
        $page_count = $model_goods->gettotalpage();
        //处理商品列表(团购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);

        foreach($goods_list as $k=>$v){
            //$goods_list[$k]['goods_attr']=unserialize($v['goods_attr']);
            unset($goods_list[$k]['goods_attr']);
            //$goods_list[$k]['brand_country_flag'] = empty($goods_list[$k]['brand_country_code']) ? '':'flag-icon-' .$goods_list[$k]['brand_country_code'];
            $goods_list[$k]['brand_country_flag'] = 'flag-icon-' .$goods_list[$k]['brand_country_code'];
            if(empty($goods_list[$k]['brand_country_zh'])){
                $goods_list[$k]['brand_country_zh'] = '';
            }
            unset($goods_list[$k]['brand_country_code']);
            switch($goods_list[$k]['transport_id']){
                //case 1:
                //    $goods_list[$k]['goods_shipping'] = '全国配送';
                //    break;
                case 2:
                    $goods_list[$k]['goods_shipping'] = 'taicang'; //仅限太仓
                    break;
                case 3:
                    $goods_list[$k]['goods_shipping'] = 'ziti'; //仅限自提
                    break;
                case 5:
                    $goods_list[$k]['goods_shipping'] = 'jiangzhehu';  //仅限江浙沪
                    break;
                case 6:
                    $goods_list[$k]['goods_shipping'] = 'baoshui'; //保税仓(全国配送)
                    break;
                default:
                    $goods_list[$k]['goods_shipping'] = 'quanguo'; //
            }
            if(empty($goods_list[$k]['goods_jingle'])){
                $goods_list[$k]['goods_jingle'] = '';
            }
            $goods_list[$k]['goods_click']= $v['goods_salenum'];
            unset($goods_list[$k]['store_id']);
            unset($goods_list[$k]['transport_id']);
        }
        if(is_array($checked_brand)){
            $checked_brand_id =  array_keys($checked_brand)[0];
            $checked_brand_name =  array_values($checked_brand)[0]['brand_name'];
            $checked_brand_new = ['brand_id'=>$checked_brand_id , 'brand_name'=>$checked_brand_name];
        }
        output_data(array('goods_list' => $goods_list,'brand_list' => $brand_array,'initial_array' => $initial_array,'attr_list' => $attr_array,'checked_brand' => $checked_brand_new,'checked_attr' => $checked_attr), mobile_page($page_count));
    }

    /**
     * 商品列表
     */
    public function goods_list206Op() {
        $model_goods = Model('goods');
        $model_search = Model('search');

        //查询条件
        $condition = array();

        // ==== 暂时不显示定金预售商品，手机端未做。  ====
        if(!empty($_GET['cate_id']) && intval($_GET['cate_id']) > 0) {
            $condition['gc_id'] = $_GET['cate_id'];
            $default_classid = intval($_GET['cate_id']);
        } elseif (!empty($_GET['keyword'])) {
            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');

            if (cookie('his_sh') == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', cookie('his_sh'));
            }
            if (strlen($_GET['keyword']) <= 30 && !in_array($_GET['keyword'],$his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setNcCookie('his_sh', implode('~', $his_sh_list),2592000); //添加历史纪录
            //从TAG中查找分类
            $goods_class_array = $model_search->getTagCategory($_GET['keyword']);
            //取出第一个分类作为默认分类，从而显示相应的属性和品牌
            $default_classid = $goods_class_array[0];

        } elseif (!empty($_GET['barcode'])) {
            $condition['goods_barcode'] = $_GET['barcode'];
        } elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }

        //if (!empty($_GET['price_from']) && intval($_GET['price_from'] > 0)) {
        //    $condition['goods_price'][] = array('egt',intval($_GET['price_from']));
        //}
        //if (!empty($_GET['price_to']) && intval($_GET['price_to'] > 0)) {
        //    $condition['goods_price'][] = array('elt',intval($_GET['price_to']));
        //}
        //if (intval($_GET['area_id']) > 0) {
        //	$condition['areaid_1'] = intval($_GET['area_id']);
        //}


        ////团购
        //if ($_GET['groupbuy'] == 1) {
        //	$condition['goods_promotion_type'][] = 1;
        //}
        //限时折扣
        if ($_GET['xianshi'] == 1) {
            $condition['goods_promotion_type'][] = 2;
        }
        ////虚拟
        //if ($_GET['virtual'] == 1) {
        //	$condition['is_virtual'] = 2;
        //}
        //所需字段
        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,goods_image,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_salenum,evaluation_count,goods_edittime";

        // 添加3个状态字段
        //$fieldstr .= ',is_virtual,is_own_shop';

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);
        if(empty($_GET['keyword']) || strlen($_GET['keyword']) > 3){
            //全文搜索搜索参数
            $indexer_searcharr = $_GET;

            //if ($_GET['own_shop'] == 1) {
            //    $indexer_searcharr['type'] = 1;
            //}
            //$indexer_searcharr['price_from'] = $price_from;
            //$indexer_searcharr['price_to'] = $price_to;

            //优先从全文索引库里查找
            list($goods_list,$indexer_count) = $model_search->indexerSearch($indexer_searcharr,$this->page);
        }

        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $model_search->getAttr($indexer_searcharr, $default_classid);
        if (!empty($goods_list)) {
            $goods_list = array_values($goods_list);
            pagecmd('setEachNum',$this->page);
            pagecmd('setTotalNum',$indexer_count);
        } else {
            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);
        }
        $page_count = $model_goods->gettotalpage();
        //处理商品列表(团购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend206($goods_list);

        foreach($goods_list as $k=>$v){
            //$goods_list[$k]['goods_attr']=unserialize($v['goods_attr']);
            unset($goods_list[$k]['goods_attr']);
            //$goods_list[$k]['brand_country_flag'] = empty($goods_list[$k]['brand_country_code']) ? '':'flag-icon-' .$goods_list[$k]['brand_country_code'];
            $goods_list[$k]['brand_country_flag'] = 'flag-icon-' .$goods_list[$k]['brand_country_code'];
            if(empty($goods_list[$k]['brand_country_zh'])){
                $goods_list[$k]['brand_country_zh'] = '';
            }
            unset($goods_list[$k]['brand_country_code']);
            switch($goods_list[$k]['transport_id']){
                //case 1:
                //    $goods_list[$k]['goods_shipping'] = '全国配送';
                //    break;
                case 2:
                    $goods_list[$k]['goods_shipping'] = 'taicang'; //仅限太仓
                    break;
                case 3:
                    $goods_list[$k]['goods_shipping'] = 'ziti'; //仅限自提
                    break;
                case 5:
                    $goods_list[$k]['goods_shipping'] = 'jiangzhehu';  //仅限江浙沪
                    break;
                case 6:
                    $goods_list[$k]['goods_shipping'] = 'baoshui'; //保税仓(全国配送)
                    break;
                default:
                    $goods_list[$k]['goods_shipping'] = 'quanguo'; //
            }
            if(empty($goods_list[$k]['goods_jingle'])){
                $goods_list[$k]['goods_jingle'] = '';
            }
            $goods_list[$k]['goods_click']= $v['goods_salenum'];
            unset($goods_list[$k]['store_id']);
            unset($goods_list[$k]['transport_id']);
        }
        if(is_array($checked_brand)){
            $checked_brand_id =  array_keys($checked_brand)[0];
            $checked_brand_name =  array_values($checked_brand)[0]['brand_name'];
            $checked_brand_new = ['brand_id'=>$checked_brand_id , 'brand_name'=>$checked_brand_name];
        }
        output_data(array('goods_list' => $goods_list,'brand_list' => $brand_array,'initial_array' => $initial_array,'attr_list' => $attr_array,'checked_brand' => $checked_brand_new,'checked_attr' => $checked_attr), mobile_page($page_count));
    }

        /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'is_own_shop desc,goods_id desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if($order == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //销量
                case '1' :
                    $result = 'goods_salenum' . ' ' . $sequence;
                    break;
                //浏览量
                case '2' :
                    $result = 'goods_click' . ' ' . $sequence;
                    break;
                //价格
                case '3' :
                    $result = 'goods_price' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }

    /**
     * 处理商品列表(团购、限时折扣、商品图片)
     */
    private function _goods_list_extend($goods_list) {
        //获取商品列表编号数组
        $goodsid_array = array();
        foreach($goods_list as $key => $value) {
            $goodsid_array[] = $value['goods_id'];
        }

        foreach ($goods_list as $key => $value) {
           // $goods_list[$key]['group_flag']     = false;
            $goods_list[$key]['xianshi_flag']   = $value['goods_promotion_type']== 2 ?true:false;

            $goods_list[$key]['goods_price'] = ncPriceFormat($value['goods_promotion_price']);
            //switch ($value['goods_promotion_type']) {
            //    case 1:
            //        $goods_list[$key]['group_flag'] = true;
            //        break;
            //    case 2:
            //        $goods_list[$key]['xianshi_flag'] = true;
            //        break;
            //}


            //商品图片url
            $goods_list[$key]['goods_image_url'] = yf_cthumb2($value['goods_image'],'');
            $goods_list[$key]['goods_image_url2'] = yf_cthumb3($value['goods_image'],'');
//            $goods_list[$key]['goods_image_url2'] = yf_cthumb($value['goods_image'],'');
            //$goods_list[$key]['goods_image_url'] = yf_cthumb($value['goods_image'],'', $value['store_id']) . '@!w220';

            unset($goods_list[$key]['goods_promotion_type']);
            unset($goods_list[$key]['goods_promotion_price']);
            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['nc_distinct']);
        }

        return $goods_list;
    }

    /**
     * 处理商品列表(团购、限时折扣、商品图片)
     */
    private function _goods_list_extend206($goods_list) {
        //获取商品列表编号数组
        $goodsid_array = array();
        foreach($goods_list as $key => $value) {
            $goodsid_array[] = $value['goods_id'];
        }

        foreach ($goods_list as $key => $value) {
            // $goods_list[$key]['group_flag']     = false;
            $goods_list[$key]['xianshi_flag']   = $value['goods_promotion_type']== 2 ?true:false;

            $goods_list[$key]['goods_price'] = $value['goods_promotion_price'];
            //switch ($value['goods_promotion_type']) {
            //    case 1:
            //        $goods_list[$key]['group_flag'] = true;
            //        break;
            //    case 2:
            //        $goods_list[$key]['xianshi_flag'] = true;
            //        break;
            //}


            //商品图片url
            //$goods_list[$key]['goods_image_url'] = yf_cthumb($value['goods_image'],'', $value['store_id']);
//            $goods_list[$key]['goods_image_url'] = yf_cthumb2($value['goods_image'],'', $value['store_id']) . '@!w100';
            $goods_list[$key]['goods_image_url'] = yf_cthumb2($value['goods_image'],'100');
            $goods_list[$key]['goods_image_url2'] = yf_cthumb3($value['goods_image'],'100');

            unset($goods_list[$key]['goods_promotion_type']);
            unset($goods_list[$key]['goods_promotion_price']);
            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['nc_distinct']);
        }

        return $goods_list;
    }


    /**
     * 商品详细页
     */
    public function goods_detailOp() {
        $goods_id = intval($_GET ['goods_id']);

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_id,true);

        if (empty($goods_detail)) {
            output_error('商品不存在');
        }

        //商品详细信息处理
        $goods_detail = $this->_goods_detail_extend($goods_detail);

        //$freight_to = ['provinceid'=>10,'cityid'=>166,'areaid'=>2068,'areainfo'=>'江苏 苏州 太仓'];
        $freight_to = '江苏-苏州-太仓';
        $freight_areaid = '2068';
        // 如果已登录 判断该商品是否已被收藏
        if ($memberId = $this->getMemberIdIfExists()) {
            $c = (int) Model('favorites')->getGoodsFavoritesCountByGoodsId($goods_id, $memberId);
            $goods_detail['is_favorate'] = $c > 0;
           // $goods_detail['cart_count'] = Model('cart')->countCartByMemberId($memberId);

            $model_member = Model('member');
            $member_area_info = $model_member->getMemberInfoByID($memberId,'member_provinceid,member_cityid,member_areaid,member_areainfo');
            if(!empty($member_area_info)){
                //$freight_to['provinceid'] = $member_area_info['member_provinceid'];
                //$freight_to['cityid'] = $member_area_info['member_cityid'];
                //$freight_to['areaid'] = $member_area_info['member_areaid'];
                //$freight_to['areainfo'] = $member_area_info['member_areainfo'];
                $freight_areaid = $member_area_info['member_areaid'];
                $freight_to = $member_area_info['member_areainfo'];
            }

        }
        $goods_detail['freight_to'] = $freight_to;

        $goods_detail['freight_text'] = '不支持的配送区域';
        $goods_detail['freight_status'] = 0;
        $model_transport = Model('transport');

        //$goods_detail['freight_info'] = $model_transport->area_freight($goods_detail['goods_info']['transport_id'],$freight_areaid);
        $freight_info = $model_transport->area_freight($goods_detail['goods_info']['transport_id'],$freight_areaid);
        $goods_detail['freight_title'] = $freight_info['transport_title'];
        if($goods_detail['goods_info']['transport_id'] ==3){
            $goods_detail['freight_title'] = '仅限上门自提';
        }
        if($goods_detail['goods_info']['is_chain'] && $goods_detail['goods_info']['transport_id']!=3 && $goods_detail['goods_info']['transport_id']!=7){
            $goods_detail['freight_title'] .= ',或上门自提!';
        }
        if (!empty($freight_info) && $freight_info['supported']) {
            $goods_detail['freight_status'] = 1;
            $goods_detail['freight_text'] = $freight_info['sprice'].' 元';
            if($freight_info['baoyou'] > 0 ){
                $goods_detail['freight_text'] .= ',满'.$freight_info['baoyou'].'包邮!';
            }
        }

		if($goods_detail){
			$model_goods_browse = Model('goods_browse')->addViewedGoods($goods_id,$memberId); //加入浏览历史数据库
			output_data($goods_detail);
		}
    }

    /**
     * 商品详细信息处理
     */
    private function _goods_detail_extend($goods_detail) {
        //整理商品规格
        unset($goods_detail['spec_list']);
        $goods_detail['spec_list'] = $goods_detail['spec_list_mobile'];
        unset($goods_detail['spec_list_mobile']);

        //整理商品图片
        unset($goods_detail['goods_image']);
        $goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);
        unset($goods_detail['goods_image_mobile']);

        //商品链接
        $goods_detail['goods_info']['goods_url'] = urlShop('goods', 'index', array('goods_id' => $goods_detail['goods_info']['goods_id']));

        //整理数据
        unset($goods_detail['goods_info']['goods_vat']);
        unset($goods_detail['goods_info']['goods_discount']);
        unset($goods_detail['goods_info']['goods_freight']);
        unset($goods_detail['goods_info']['is_own_shop']);
        unset($goods_detail['goods_info']['color_id']);
        unset($goods_detail['goods_info']['gc_id']);
        unset($goods_detail['goods_info']['gc_name']);
        unset($goods_detail['goods_info']['store_id']);
        unset($goods_detail['goods_info']['store_name']);
        unset($goods_detail['goods_info']['brand_id']);
        unset($goods_detail['goods_info']['brand_name']);
        unset($goods_detail['goods_info']['type_id']);
        unset($goods_detail['goods_info']['goods_image']);
        unset($goods_detail['goods_info']['goods_body']);
        unset($goods_detail['goods_info']['mobile_body']);
        unset($goods_detail['goods_info']['goods_state']);
        unset($goods_detail['goods_info']['goods_stateremark']);
        unset($goods_detail['goods_info']['goods_verify']);
        unset($goods_detail['goods_info']['goods_verifyremark']);
        unset($goods_detail['goods_info']['goods_lock']);
        unset($goods_detail['goods_info']['goods_addtime']);
        unset($goods_detail['goods_info']['goods_edittime']);
        unset($goods_detail['goods_info']['goods_selltime']);
        unset($goods_detail['goods_info']['goods_show']);
        unset($goods_detail['goods_info']['goods_commend']);
        unset($goods_detail['goods_info']['explain']);
        unset($goods_detail['goods_info']['cart']);
        unset($goods_detail['goods_info']['buynow']);
        unset($goods_detail['goods_info']['buynow_text']);
        unset($goods_detail['goods_info']['goods_costprice']);
        unset($goods_detail['goods_info']['areaid_1']);
        unset($goods_detail['goods_info']['areaid_2']);
        unset($goods_detail['goods_info']['gc_id_2']);
        unset($goods_detail['goods_info']['gc_id_3']);
        unset($goods_detail['goods_info']['plateid_top']);
        unset($goods_detail['goods_info']['plateid_bottom']);
        unset($goods_detail['goods_info']['goods_serial']);  //商品货号
        unset($goods_detail['goods_info']['sup_id']);  //
        unset($goods_detail['goods_info']['virtual_limit']);  //
        unset($goods_detail['goods_info']['virtual_indate']);  //
        unset($goods_detail['goods_info']['is_virtual']);  //
        unset($goods_detail['goods_info']['virtual_invalid_refund']);  //
        $goods_detail['goods_info']['goods_storage_status'] = $goods_detail['goods_info']['goods_storage'] > 0 ? '1':'0';
        unset($goods_detail['goods_info']['goods_storage']);  //商品库存
        unset($goods_detail['goods_info']['goods_storage_alarm']);  //商品库存报警
        unset($goods_detail['goods_info']['evaluation_good_star']);  //
        unset($goods_detail['goods_info']['goods_stcids']);  //商铺分类ID
        unset($goods_detail['goods_info']['goods_barcode']);  //商品货号
        unset($goods_detail['goods_info']['transport_title']);
        unset($goods_detail['goods_info']['goods_custom']); //商品自定义属性
        if(is_array($goods_detail['goods_info']['xianshi_info'])){
            unset($goods_detail['goods_info']['xianshi_info']['xianshi_goods_id']);
            unset($goods_detail['goods_info']['xianshi_info']['goods_id']);
            unset($goods_detail['goods_info']['xianshi_info']['store_id']);
            unset($goods_detail['goods_info']['xianshi_info']['goods_name']);
            unset($goods_detail['goods_info']['xianshi_info']['goods_price']);
            unset($goods_detail['goods_info']['xianshi_info']['goods_image']);
            unset($goods_detail['goods_info']['xianshi_info']['gc_id_1']);
            unset($goods_detail['goods_info']['xianshi_info']['goods_url']);
            unset($goods_detail['goods_info']['xianshi_info']['image_url']);
            unset($goods_detail['goods_info']['xianshi_info']['state']);
            if($goods_detail['goods_info']['xianshi_info']['start_time']- TIMESTAMP > 0){
                $goods_detail['goods_info']['xianshi_info']['count_down'] = date('Y-m-d H:i:s',$goods_detail['goods_info']['xianshi_info']['start_time']);
                $goods_detail['goods_info']['xianshi_info']['count_down_text'] = '距开始';
            }else if($goods_detail['goods_info']['xianshi_info']['end_time'] > 0 && $goods_detail['goods_info']['xianshi_info']['end_time'] - time() < 3600*24){
                $goods_detail['goods_info']['xianshi_info']['count_down'] = date('Y-m-d H:i:s',$goods_detail['goods_info']['xianshi_info']['end_time']);
                $goods_detail['goods_info']['xianshi_info']['count_down_text'] = '距结束';
            }else{
                $goods_detail['goods_info']['xianshi_info']['count_down'] = '';
                $goods_detail['goods_info']['xianshi_info']['count_down_text'] = '';
            }

        }

        return $goods_detail;
    }

    /**
     * 商品详细页
     */
    public function goods_bodyOp() {
       //yefeng header("Access-Control-Allow-Origin:*");
        $goods_id = intval($_GET ['goods_id']);

        $model_goods = Model('goods');

        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');
        $goods_common_info = $model_goods->getGoodsCommonInfoByID($goods_info['goods_commonid']);

        //Tpl::output('goods_common_info', $goods_common_info);
        //Tpl::showpage('goods_body');
        output_data($goods_common_info['goods_body']);
    }

	
	public function auto_completeOp() {
		if ($_GET['term'] == '' && cookie('his_sh') != '') {
            $corrected = explode('~', cookie('his_sh'));
            if ($corrected != '' && count($corrected) !== 0) {
                $data = array();
                foreach ($corrected as $word)
                {
                    $row['id'] = $word;
                    $row['label'] = $word;
                    $row['value'] = $word;
                    $data[] = $row;
                }
                output_data($data);
            }
            return;
        }
		
        if (!C('fullindexer.open')) return;
		//output_error('1000');
        try {
            require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
            $obj_doc = new XSDocument();
            $obj_xs = new XS(C('fullindexer.appname'));
            $obj_index = $obj_xs->index;
            $obj_search = $obj_xs->search;
            $obj_search->setCharset(CHARSET);
            $corrected = $obj_search->getExpandedQuery($_GET['term']);
            if (count($corrected) !== 0) {
                $data = array();
                foreach ($corrected as $word)
                {
                    $row['id'] = $word;
                    $row['label'] = $word;
                    $row['value'] = $word;
                    $data[] = $row;
                }
                output_data($data);
            }
        } catch (XSException $e) {
            if (is_object($obj_index)) {
                $obj_index->flushIndex();
            }
			output_error($e->getMessage());
			//             Log::record('search\auto_complete'.$e->getMessage(),Log::RUN);
        }
		
		
	}



      /**
     * 商品详细页运费显示
     *
     * @return unknown
     */
    public function calcOp(){
        $area_id = intval($_GET['area_id']);
        $goods_id = intval($_GET['goods_id']);
        if(empty($area_id) ||empty($goods_id)){
            output_data('参数错误');
        }
        output_data($this->_calc($area_id, $goods_id));
    }

    public function _calc($area_id,$goods_id){
    $goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'transport_id,store_id,goods_freight');
    $store_info = Model('store')->getStoreInfoByID($goods_info['store_id']);
    if ($area_id <= 0) {
        if (strpos($store_info['deliver_region'],'|')) {
            $store_info['deliver_region'] = explode('|', $store_info['deliver_region']);
            $store_info['deliver_region_ids'] = explode(' ', $store_info['deliver_region'][0]);
        }
        $area_id = intval($store_info['deliver_region_ids'][1]);
        $area_name = $store_info['deliver_region'][1];
    }
    if ($goods_info['transport_id'] && $area_id > 0) {
        $freight_total = Model('transport')->calc_transport(intval($goods_info['transport_id']),$area_id);
        if ($freight_total > 0) {
            if ($store_info['store_free_price'] > 0) {
                if ($freight_total >= $store_info['store_free_price']) {
                    $freight_total = '免运费';
                } else {
                    $freight_total = '运费：'.$freight_total.' 元，店铺满 '.$store_info['store_free_price'].' 元 免运费';
                }
            } else {
                $freight_total = '运费：'.$freight_total.' 元';
            }
        } else {
            if ($freight_total === false) {
                $if_store = false;
            }
            $freight_total = '免运费';
        }
    } else {
        $freight_total = $goods_info['goods_freight'] > 0 ? '运费：'.$goods_info['goods_freight'].' 元' : '免运费';
    }

    return array('content'=>$freight_total,'if_store_cn'=>$if_store === false ? '无货' : '有货','if_store'=>$if_store === false ? false : true,'area_name'=>$area_name ? $area_name : '全国');
}

    public function _calc2($area_id,$goods_id){
        $goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'transport_id,is_chain');
        $freight_info['freight_status'] = 0;
        if (!empty($goods_info['transport_id'])) {
            $model_transport = Model('transport');
            $freight_info_array = $model_transport->area_freight($goods_info['transport_id'],$area_id);
            $freight_info['freight_title'] = $freight_info_array['transport_title'];
            if($goods_info['is_chain']){
                $freight_info['freight_title'] .= ',或上门自提!';
            }
            if (!empty($freight_info_array) && $freight_info_array['supported']) {
                $freight_info['freight_status'] = 1;
                $freight_info['freight_text'] = '运费：'.$freight_info_array['sprice'].' 元';
                if($freight_info_array['baoyou'] > 0 ){
                    $freight_info['freight_text'] .= ',满'.$freight_info_array['baoyou'].'包邮!';
                }
            } else {
                $freight_info['freight_text'] = '不支持的配送区域';
            }
        } else {
            $freight_info['freight_text'] = '不支持的配送区域';
        }

        return $freight_info;
    }

    public function freight_toOp(){
        $area_id = intval($_POST['area_id']);
        $goods_id = intval($_POST['goods_id']);

        $area_model = Model('area');
        $area_array = $area_model->getAreas();
        // $area_parent = $area_array['parent'];
        foreach ($area_array['parent'] as $key=>$value){
            if($key == $area_id) {
                $city_id = $value;
                break;
            }
        }
        if(empty($city_id)){
            output_error('选择的地址错误');
        }
        foreach ($area_array['parent'] as $key=>$value){
            if($key == $city_id) {
                $province_id = $value;
                break;
            }
        }
        if(empty($province_id)){
            output_error('选择的地址错误');
        }
        foreach ($area_array['name'] as $key=>$value){
            switch ($key){
                case $area_id:
                    $area_name = $value;
                    break;
                case $city_id:
                    $city_name = $value;
                    break;
                case $province_id:
                    $province_name = $value;
                    break;
            }
        }
        $area_info = $province_name.'-'.$city_name.'-'.$area_name;

        if($memberId = $this->getMemberIdIfExists()){//登录用户
            $model_member = Model('member');
            $model_member->editMember(['member_id'=>$memberId],['member_provinceid'=>$province_id,'member_cityid'=>$city_id,'member_areaid'=>$area_id,'member_areainfo'=>$area_info]);
        }
        $freight_info = $this->_calc2($area_id, $goods_id);
        $freight_info['freight_to'] = $area_info;
        output_data($freight_info);
    }



	   /*分店地址*/
        public function store_o2o_addrOp(){
            $store_id = intval($_GET ['store_id']);
            $model_store_map = Model('store_map');
            $addr_list_source = $model_store_map->getStoreMapList($store_id);
            foreach ($addr_list_source as $k => $v) {
            	$addr_list_tmp = array();
            	$addr_list_tmp['key'] = $k;
            	$addr_list_tmp['map_id'] = $v['map_id'];
            	$addr_list_tmp['name_info'] = $v['name_info'];
            	$addr_list_tmp['address_info'] = $v['address_info'];
            	$addr_list_tmp['phone_info'] = $v['phone_info'];
            	$addr_list_tmp['bus_info'] = $v['bus_info'];
            	$addr_list_tmp['province'] = $v['baidu_province'];
            	$addr_list_tmp['city'] = $v['baidu_city'];
            	$addr_list_tmp['district'] = $v['baidu_district'];
            	$addr_list_tmp['street'] = $v['baidu_street'];
            	$addr_list_tmp['lng'] = $v['baidu_lng'];
            	$addr_list_tmp['lat'] = $v['baidu_lat'];
            	$addr_list[] = $addr_list_tmp;
            }
            output_data(array('addr_list'=>$addr_list));
        }

	/**
     * 商品评价
     */
    public function goods_evaluateOp() {
		$goods_id = intval($_GET['goods_id']);
		if($goods_id <=0){
			output_error('产品不存在');
		}
		
//      $goodsevallist = $this->_get_comments($goods_id, $_GET['type'], $this->page);
        $goodsevallist = $this->_get_comments($goods_id, $_GET['type'], 10);
		$model_evaluate_goods = Model("evaluate_goods");
		$page_count = $model_evaluate_goods->gettotalpage();		
		output_data(array('goods_eval_list'=>$goodsevallist),mobile_page($page_count));
	}

	private function _get_comments($goods_id, $type, $page) {
        $condition = array();
        $condition['geval_goodsid'] = $goods_id;
        switch ($type) {
            case '1':
                $condition['geval_scores'] = array('in', '5,4');
                Tpl::output('type', '1');
                break;
            case '2':
                $condition['geval_scores'] = array('in', '3,2');
                Tpl::output('type', '2');
                break;
            case '3':
                $condition['geval_scores'] = array('in', '1');
                Tpl::output('type', '3');
                break;
        }

        //查询商品评分信息
        $model_evaluate_goods = Model("evaluate_goods");
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, $page);
        foreach($goodsevallist as $key=>$value){
            $goodsevallist[$key]['add_time'] = date("Y-m-d H:i:s",$value['geval_addtime']);
            if($goodsevallist[$key]['geval_isanonymous']){
                $goodsevallist[$key]['geval_frommembername'] = '匿名';
                $goodsevallist[$key]['member_avatar'] = 'http://img2.htths.com/shop/common/default_user_portrait.gif';
//            }elseif(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|18[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$goodsevallist[$key]['geval_frommembername'])){
          // http://www.lao8.org/article_1368/zhengze_shoujihaoma
            }elseif(preg_match("/^1[34578]\d{9}$/",$goodsevallist[$key]['geval_frommembername'])){
                $goodsevallist[$key]['geval_frommembername'] = encryptShow($goodsevallist[$key]['geval_frommembername'],4,4);
                $goodsevallist[$key]['member_avatar'] = getMemberAvatarForID2($value['geval_frommemberid']);
            }else{
              $membername_length = strlen($goodsevallist[$key]['geval_frommembername']);
              if ($membername_length > 3) {
                $goodsevallist[$key]['geval_frommembername'] = encryptShow($goodsevallist[$key]['geval_frommembername'],2,2);
              } elseif ($membername_length > 2) {
                $goodsevallist[$key]['geval_frommembername'] = encryptShow($goodsevallist[$key]['geval_frommembername'],2,1);
              }
//              $goodsevallist[$key]['geval_frommembername'] = encryptShow($goodsevallist[$key]['geval_frommembername'],1,1);

              $goodsevallist[$key]['member_avatar'] = getMemberAvatarForID2($value['geval_frommemberid']);
            }
		}
		return $goodsevallist;
		//Tpl::output('goodsevallist',$goodsevallist);
        //Tpl::output('show_page',$model_evaluate_goods->showpage('5'));
    }

    /**
     * 看了又看/猜你喜欢（同分类本店随机商品）
     */
    public function goods_rand_listOp() {
        //$gc_id_1=intval($_GET["gc_id_1"]);
        $goods_id=intval($_GET["goods_id"]);
        $number=$_GET["number"] !='' ?intval($_GET["number"]):5;
        $model_goods = Model('goods');
        $good_info = $model_goods->getGoodsInfoByID($goods_id);
        $gc_id_1= $good_info['gc_id_1'];
        $goods_rand_list = $model_goods->getGoodsGcStoreRandList($gc_id_1, 1, $goods_id, $number,'goods_id,goods_name,goods_price,goods_marketprice,goods_image');
        foreach($goods_rand_list as $key=>$goods_rand_item){
            $goods_rand_item['goods_price'] = ncPriceFormat($goods_rand_item['goods_price']);
            $goods_rand_item_new[$key] = $goods_rand_item;
            $goods_rand_item_new[$key]['goods_image_url'] = cthumb($goods_rand_item['goods_image'], 240);
        }
        output_data($goods_rand_item_new);
    }


}
