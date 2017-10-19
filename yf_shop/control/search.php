<?php
/**
 * 商品列表
 * @gyf
 */


defined('ByYfShop') or exit('非法进入,IP记录...');

class searchControl extends BaseHomeControl {


    //每页显示商品数
    const PAGESIZE = 24;

    //模型对象
    private $_model_search;

    public function indexOp() {
        Language::read('home_goods_class_index');
        $this->_model_search = Model('search');
        //显示左侧分类
        //默认分类，从而显示相应的属性和品牌
        $default_classid = intval($_GET['cate_id']);
        if (intval($_GET['cate_id']) > 0) {
            $goods_class_array = $this->_model_search->getLeftCategory(array($_GET['cate_id']));
        } elseif ($_GET['keyword'] != '') {
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
            setNcCookie('his_sh', implode('~', $his_sh_list),2592000);
            //从TAG中查找分类
            $goods_class_array = $this->_model_search->getTagCategory($_GET['keyword']);
            //取出第一个分类作为默认分类，从而显示相应的属性和品牌
            $default_classid = $goods_class_array[0];
            $goods_class_array = $this->_model_search->getLeftCategory($goods_class_array, 1);;
        }
        Tpl::output('goods_class_array', $goods_class_array);
        Tpl::output('default_classid', $default_classid);


        //优先从全文索引库里查找
        if( empty($_GET['keyword']) or strlen($_GET['keyword']) > 3 ){
            //全文搜索搜索参数
            $indexer_searcharr = $_GET;
            list($goods_list,$indexer_count) = $this->_model_search->indexerSearch($indexer_searcharr,self::PAGESIZE);
        }

        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $this->_model_search->getAttr($_GET, $default_classid);
        Tpl::output('brand_array', $brand_array);
        Tpl::output('initial_array', $initial_array);
        Tpl::output('attr_array', $attr_array);
        Tpl::output('checked_brand', $checked_brand);
        Tpl::output('checked_attr', $checked_attr);

        $model_goods = Model('goods');
        if (!is_null($goods_list)) {
            //全文搜索 
            pagecmd('setEachNum',self::PAGESIZE);
            pagecmd('setTotalNum',$indexer_count);

        } else {
            //查库搜索

            //处理排序
            $order = 'is_own_shop desc,goods_id desc';
            if (in_array($_GET['key'],array('1','2','3'))) {
                $sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
                $order = str_replace(array('1','2','3'), array('goods_salenum','goods_click','goods_promotion_price'), $_GET['key']);
                $order .= ' '.$sequence;
            }

            // 字段
            $fields = "goods_id,goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,brand_id,color_id,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,evaluation_good_star,evaluation_count,is_virtual,areaid_1,goods_attr";

            $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
            $condition = array();
            if (isset($goods_param['class'])) {
                $condition['gc_id_'.$goods_param['class']['depth']] = $goods_param['class']['gc_id'];
            }
            if (intval($_GET['b_id']) > 0) {
                $condition['brand_id'] = intval($_GET['b_id']);
            }
            if ($_GET['keyword'] != '') {
                $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
            }
            if (intval($_GET['area_id']) > 0) {
                $condition['areaid_1'] = intval($_GET['area_id']);
            }
            if ($_GET['type'] == 1) {
                $condition['is_own_shop'] = 1;
            }

            if (isset($goods_param['goodsid_array'])){
                $condition['goods_id'] = array('in', $goods_param['goodsid_array']);
            }
            if ($goods_class[$default_classid]['show_type'] == 1) {
                $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fields, $order, self::PAGESIZE);
            } else {
                $count = $model_goods->getGoodsOnlineCount($condition,"distinct goods_commonid");
                $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, self::PAGESIZE, $order, 0, 'goods_commonid', false, $count);
            }
        }
        Tpl::output('show_page1', $model_goods->showpage(4));
        Tpl::output('show_page', $model_goods->showpage(5));

        if (!empty($goods_list)) {
            if (is_null($indexer_count)) {
                //查库搜索
                $commonid_array = array(); // 商品公共id数组
                $storeid_array = array();       // 店铺id数组
                foreach ($goods_list as $value) {
                    $commonid_array[] = $value['goods_commonid'];
                    $storeid_array[] = $value['store_id'];
                }
                $commonid_array = array_unique($commonid_array);
                $storeid_array = array_unique($storeid_array);
                // 商品多图
                $goodsimage_more = $model_goods->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)), '*', 'is_default desc,goods_image_id asc');
                // 店铺
                $store_list = Model('store')->getStoreMemberIDList($storeid_array);
            }

            //搜索的关键字
            $search_keyword = $_GET['keyword'];
            foreach ($goods_list as $key => $value) {
                if (is_null($indexer_count)) {
                    // 商品多图
                    
                    if ($goods_class[$default_classid]['show_type'] == 1) {
                        foreach ($goodsimage_more as $v) {
                            if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                                $goods_list[$key]['image'][] = $v['goods_image'];
                            }
                        }
                    } else {
                        foreach ($goodsimage_more as $v) {
                            if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $v['is_default'] == 1) {
                                $goods_list[$key]['image'][] = $v['goods_image'];
                            }
                        }
                    }
                    // 店铺的开店会员编号
                    $store_id = $value['store_id'];
                    $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                    $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];                    
                }

                //将关键字置红
                if ($search_keyword){
                    $goods_list[$key]['goods_name_highlight'] = str_replace($search_keyword,'<font style="color:#f00;">'.$search_keyword.'</font>',$value['goods_name']);
                } else {
                    $goods_list[$key]['goods_name_highlight'] = $value['goods_name'];
                }
            }
        }
        Tpl::output('goods_list', $goods_list);
        if ($_GET['keyword'] != ''){
            Tpl::output('show_keyword',  $_GET['keyword']);
        } else {
            Tpl::output('show_keyword',  $goods_param['class']['gc_name']);
        }

        $model_goods_class = Model('goods_class');

        // SEO
        if ($_GET['keyword'] == '') {
            $seo_class_name = $goods_param['class']['gc_name'];
            if (is_numeric($_GET['cate_id']) && empty($_GET['keyword'])) {
                $seo_info = $model_goods_class->getKeyWords(intval($_GET['cate_id']));
                if (empty($seo_info[1])) {
                    $seo_info[1] = C('site_name') . ' - ' . $seo_class_name;
                }
                Model('seo')->type($seo_info)->param(array('name' => $seo_class_name))->show();
            }
        } elseif ($_GET['keyword'] != '') {
            Tpl::output('html_title', (empty($_GET['keyword']) ? '' : $_GET['keyword'] . ' - ') . C('site_name') . L('nc_common_search'));
        }

        // 当前位置导航
        $nav_link_list = $model_goods_class->getGoodsClassNav(intval($_GET['cate_id']));
        Tpl::output('nav_link_list', $nav_link_list );

        // 得到自定义导航信息
        $nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0;
        Tpl::output('index_sign', $nav_id);

        // 地区
        $province_array = Model('area')->getTopLevelAreas();
        Tpl::output('province_array', $province_array);

        loadfunc('search');

        // 浏览过的商品
        $viewed_goods = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);
        Tpl::output('viewed_goods',$viewed_goods);
        Tpl::display2('search');
    }

    /**
     * 获得推荐商品
     */
    public function get_booth_goodsOp() {
        $gc_id = $_GET['cate_id'];
        if ($gc_id <= 0) {
            return false;
        }
        // 获取分类id及其所有子集分类id
        $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
        if (empty($goods_class[$gc_id])) {
            return false;
        }
        $child = (!empty($goods_class[$gc_id]['child'])) ? explode(',', $goods_class[$gc_id]['child']) : array();
        $childchild = (!empty($goods_class[$gc_id]['childchild'])) ? explode(',', $goods_class[$gc_id]['childchild']) : array();
        $gcid_array = array_merge(array($gc_id), $child, $childchild);
        // 查询添加到推荐展位中的商品id
        $boothgoods_list = Model('p_booth')->getBoothGoodsList(array('gc_id' => array('in', $gcid_array)), 'goods_id', 0, 4, 'rand()');
        if (empty($boothgoods_list)) {
            return false;
        }

        $goodsid_array = array();
        foreach ($boothgoods_list as $val) {
            $goodsid_array[] = $val['goods_id'];
        }

        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_count";
        $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id' => array('in', $goodsid_array)), $fieldstr);
        if (empty($goods_list)) {
            return false;
        }

        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('goods.booth', 'null_layout');
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
                exit(json_encode($data));
            }
            return;
        }
        if (!C('fullindexer.open')) return;
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
                exit(json_encode($data));
            }
        } catch (XSException $e) {
            if (is_object($obj_index)) {
                $obj_index->flushIndex();
            }
//             Log::record('search\auto_complete'.$e->getMessage(),Log::RUN);
        }
    }

    /**
     * 获得猜你喜欢
     */
    public function get_guesslikeOp(){
        $goodslist = Model('goods_browse')->getGuessLikeGoods($_SESSION['member_id'], 20);
        if(!empty($goodslist)){
            Tpl::output('goodslist',$goodslist);
            Tpl::showpage('goods_guesslike','null_layout');
        }
    }
}