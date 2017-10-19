<?php
/**
 * 购物车操作
 * @叶枫
 */

defined('ByYfShop') or exit('非法进入,IP记录...');
class cartControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');

        $op = isset($_GET['op']) ? $_GET['op'] : $_POST['op'];

        //允许不登录就可以访问的op
        $op_arr = array('ajax_load','add','del');
        if (!in_array($op,$op_arr) && !$_SESSION['member_id'] ){
            redirect(urlLogin('login', 'index', array('ref_url' => request_uri())));
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }

    /**
     * 购物车首页
     */
    public function indexOp() {
        //$model_cart = Model('cart');
        //
        ////购物车列表
        //$cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
        //
        //$store_cart_list = $this->_get_cart_info($cart_list);
        //
        //Tpl::output('store_cart_list',$store_cart_list);

        //店铺信息
        //$store_list = Model('store')->getStoreMemberIDList(array(1));
        //$store_list = array("1"=>array("store_id"=>"1","member_id"=>"1","store_domain"=>null));
        //Tpl::output('store_list',$store_list);

        //// 店铺优惠券,代金券
        //$condition = array();
        //$condition['voucher_t_gettype'] = 3;
        //$condition['voucher_t_state'] = 1;
        //$condition['voucher_t_end_date'] = array('gt', time());
        //$condition['voucher_t_mgradelimit'] = array('elt', $this->member_info['level']);
        //$condition['voucher_t_store_id'] = array('in', array_keys($store_cart_list));
        //$voucher_template = Model('voucher')->getVoucherTemplateList($condition);
        //$voucher_template = array_under_reset($voucher_template, 'voucher_t_store_id', 2);
        //Tpl::output('voucher_template', $voucher_template);

        //标识 购买流程执行第几步
        //Tpl::output('buy_step','step1');
        //Tpl::display2(empty($cart_list) ? 'cart_empty' : 'cart');
        Tpl::display2('cart');
    }

    public function cart_listOp() {
        $model_cart = Model('cart');
        //购物车列表
        $cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));

        //$store_cart_list = $this->_get_cart_info($cart_list);
        $logic_cart = Logic('cart');
        $store_cart_list = $logic_cart->_get_cart_info($cart_list);
        output_data($store_cart_list);
    }


    /**
     * 异步查询购物车
     */
    public function ajax_loadOp() {
        $model_cart = Model('cart');
        if ($_SESSION['member_id']){
            //登录后
            $cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
            $cart_array = array();
            if(!empty($cart_list)){
                foreach ($cart_list as $k => $cart){
                    $cart_array['list'][$k]['cart_id'] = $cart['cart_id'];
                    $cart_array['list'][$k]['goods_id'] = $cart['goods_id'];
                    $cart_array['list'][$k]['goods_name'] = $cart['goods_name'];
                    $cart_array['list'][$k]['goods_price']  = $cart['goods_price'];
                    $cart_array['list'][$k]['goods_image']  = thumb($cart,60);
                    $cart_array['list'][$k]['goods_num'] = $cart['goods_num'];
                    $cart_array['list'][$k]['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
                }
            }
        } else {
            //登录前
            $cart_list = $model_cart->listCart('cookie');
            foreach ($cart_list as $key => $cart){
                $value = array();
                $value['cart_id'] = $cart['goods_id'];
                $value['goods_id'] = $cart['goods_id'];
                $value['goods_name'] = $cart['goods_name'];
                $value['goods_price'] = $cart['goods_price'];
                $value['goods_num'] = $cart['goods_num'];
                $value['goods_image'] = thumb($cart,60);
                $value['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
                $cart_array['list'][] = $value;
            }
        }
        setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
        $cart_array['cart_all_price'] = ncPriceFormat($model_cart->cart_all_price);
        $cart_array['cart_goods_num'] = $model_cart->cart_goods_num;
        $cart_array['cart_all_weight'] = $model_cart->cart_all_weight;
        if ($_GET['type'] == 'html') {
            Tpl::output('cart_list',$cart_array);
            Tpl::display('cart_mini','null_layout');
        } else {
            $cart_array = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($cart_array) : $cart_array;
            $json_data = json_encode($cart_array);
            if (isset($_GET['callback'])) {
                $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
            }
            exit($json_data);
        }

    }

    /**
     * 加入购物车，登录后存入购物车表
     * 存入COOKIE，由于COOKIE长度限制，最多保存5个商品
     * 未登录不能将优惠套装商品加入购物车，登录前保存的信息以goods_id为下标
     *
     */
    public function addOp() {
        $model_goods = Model('goods');
        $logic_buy_1 = Logic('buy_1');
        if (is_numeric($_GET['goods_id'])) {

            //商品加入购物车(默认)
            $goods_id = intval($_GET['goods_id']);
            $quantity = intval($_GET['quantity']);
            if ($goods_id <= 0) return ;
            $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);

            //团购
//            $logic_buy_1->getGroupbuyInfo($goods_info);

            //限时折扣  判断商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
            $logic_buy_1->getXianshiInfo($goods_info,$quantity);

            $this->_check_goods($goods_info,$_GET['quantity']);


        }

        //已登录状态，存入数据库,未登录时，存入COOKIE
        if($_SESSION['member_id']) {
            $save_type = 'db';
            $goods_info['buyer_id'] = $_SESSION['member_id'];
        } else {
            $save_type = 'cookie';
        }
        $model_cart = Model('cart');
        $insert = $model_cart->addCart($goods_info,$save_type,$quantity);
        if ($insert) {
            //购物车商品种数记入cookie
            setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
            $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => ncPriceFormat($model_cart->cart_all_price));
            output_data($data);
        } else {
            //$data = array('state'=>'false');
            output_error('加入购物车失败');
        }
        //exit(json_encode($data));
    }

    /**
     * 推荐组合加入购物车
     */
    public function add_combOp() {
        if (!preg_match('/^[\d|]+$/', $_GET['goods_ids'])) {
            exit(json_encode(array('state'=>'false')));
        }

        $model_goods = Model('goods');
        $logic_buy_1 = Logic('buy_1');

        if (!$_SESSION['member_id']) {
            exit(json_encode(array('msg'=>'请先登录','UTF-8')));
        }

        $goods_id_array = explode('|', $_GET['goods_ids']);

        $model_goods = Model('goods');
        $goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);

        foreach ($goods_list as $goods) {
            $this->_check_goods($goods,1);
        }

        //团购
        $logic_buy_1->getGroupbuyCartList($goods_list);

        //限时折扣
        $logic_buy_1->getXianshiCartList($goods_list);

        $model_cart = Model('cart');
        foreach ($goods_list as $goods_info) {
            $cart_info = array();
            $cart_info['store_id']  = $goods_info['store_id'];
            $cart_info['goods_id']  = $goods_info['goods_id'];
            $cart_info['goods_name'] = $goods_info['goods_name'];
            $cart_info['goods_price'] = $goods_info['goods_price'];
            $cart_info['goods_num']   = 1;
            $cart_info['goods_image'] = $goods_info['goods_image'];
            $cart_info['store_name'] = $goods_info['store_name'];
            $quantity = 1;
            //已登录状态，存入数据库,未登录时，存入COOKIE
            if($_SESSION['member_id']) {
                $save_type = 'db';
                $cart_info['buyer_id'] = $_SESSION['member_id'];
            } else {
                $save_type = 'cookie';
            }
            $insert = $model_cart->addCart($cart_info,$save_type,$quantity);
            if ($insert) {
                //购物车商品种数记入cookie
                setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
                $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => ncPriceFormat($model_cart->cart_all_price));
            } else {
                $data = array('state'=>'false');
                exit(json_encode($data));
            }
        }
        exit(json_encode($data));
    }

    /**
     * 检查商品是否符合加入购物车条件
     * @param unknown $goods
     * @param number $quantity
     */
    private function _check_goods($goods_info, $quantity) {
        if(empty($quantity)) {
            exit(json_encode(array('msg'=>Language::get('wrong_argument','UTF-8'))));
        }
        if(empty($goods_info)) {
            exit(json_encode(array('msg'=>Language::get('cart_add_goods_not_exists','UTF-8'))));
        }
        if ($goods_info['store_id'] == $_SESSION['store_id']) {
            exit(json_encode(array('msg'=>Language::get('cart_add_cannot_buy','UTF-8'))));
        }
        if(intval($goods_info['goods_storage']) < 1) {
            exit(json_encode(array('msg'=>Language::get('cart_add_stock_shortage','UTF-8'))));
        }
        if(intval($goods_info['goods_storage']) < $quantity) {
            exit(json_encode(array('msg'=>Language::get('cart_add_too_much','UTF-8'))));
        }
        if ($goods_info['is_virtual']) {
            exit(json_encode(array('msg'=>'该商品不允许加入购物车，请直接购买','UTF-8')));
        }
        if(!empty($goods_info['xianshi_info'])){
            exit(json_encode(array('msg'=>'促销活动仅限App购买!','UTF-8')));
            if($quantity > $goods_info['xianshi_info']['upper_limit']){
                exit(json_encode(array('msg'=>'本商品限购'.$goods_info['xianshi_info']['upper_limit'].'件','UTF-8')));
            }
            if($goods_info['xianshi_info']['start_time'] > time()) {
                exit(json_encode(array('msg'=>'活动暂未开始','UTF-8')));
            }
        }
    }

    /**
     * 购物车更新商品数量
     */
    public function updateOp() {
        $cart_id    = intval(abs($_GET['cart_id']));
        $quantity   = intval(abs($_GET['quantity']));
        $if_chain   = intval(abs($_GET['if_chain']));


        if(empty($cart_id) || empty($quantity)) {
            exit(json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8'))));
        }

        $model_cart = Model('cart');
        $logic_buy_1 = logic('buy_1');

        //存放返回信息
        $return = array();

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));

        //普通商品
        $goods_id = intval($cart_info['goods_id']);
        $goods_info = $logic_buy_1->getGoodsOnlineInfo($goods_id,$quantity);
        if(empty($goods_info)) {
            $return['state'] = 'invalid';
            $return['msg'] = '商品已被下架';
            $return['subtotal'] = 0;
            QueueClient::push('delCart', array('buyer_id'=>$_SESSION['member_id'],'cart_ids'=>array($cart_id)));
            exit(json_encode($return));
        }

        $quantity = $goods_info['goods_num'];

        if(intval($goods_info['goods_storage']) < $quantity) {
            $return['state'] = 'shortage';
            $return['msg'] = '库存不足';
            $return['goods_num'] = $goods_info['goods_storage'];
            $return['goods_price'] = $goods_info['goods_price'];
            $return['subtotal'] = $goods_info['goods_price'] * intval($goods_info['goods_storage']);
            $model_cart->editCart(array('goods_num'=>$goods_info['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
            exit(json_encode($return));
        }

        $data = array();
        $data['goods_num'] = $quantity;
        $data['goods_price'] = $goods_info['goods_price'];
        $update = $model_cart->editCart($data,array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
        if ($update) {
            $condition = array('buyer_id' => $_SESSION['member_id']);
            $cart_list  = $model_cart->listCart('db', $condition);
            $store_cart_list = $this->_get_cart_info($cart_list);
            $store_cart_list['if_chain'] = $if_chain;
            $return = $store_cart_list;
        } else {
            $return = array('msg'=>Language::get('cart_update_buy_fail','UTF-8'));
        }
        exit(json_encode($return));
    }

    /**
     * 购物车删除单个商品，未登录前使用cart_id即为goods_id
     */
    public function delOp() {
        $cart_id = intval($_GET['cart_id']);
        if($cart_id < 0) return ;
        $model_cart = Model('cart');
        $data = array();
        if ($_SESSION['member_id']) {
            //登录状态下删除数据库内容
            $delete = $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
            if($delete) {
                $condition = array('buyer_id' => $_SESSION['member_id']);
                $cart_list  = $model_cart->listCart('db', $condition);
                $store_cart_list = $this->_get_cart_info($cart_list);
            } else {
                output_error('商品删除失败');
            }
        } else {
            //未登录时删除cookie的购物车信息
            //$delete = $model_cart->delCart('cookie',array('goods_id'=>$cart_id));
            //if($delete) {
            //    $data['state'] = 'true';
            //    $data['quantity'] = $model_cart->cart_goods_num;
            //    $data['amount'] = $model_cart->cart_all_price;
            //}
            output_error('请重新登录!');
        }
        setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
        //$json_data = json_encode($data);
        //if (isset($_GET['callback'])) {
        //    $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        //}
        //exit($json_data);
        output_data($store_cart_list);
    }

    /**
     * 更改购物车勾选状态
     */
    public function cart_checkedOp() {
        $cart_id = explode(',', $_POST['cart_id']);
        $cart_checked = intval($_POST['cart_checked']);

        if(empty($cart_id) ) {
            output_error('参数错误');
        }

        $model_cart = Model('cart');

        $data = array();
        $data['cart_checked'] = $cart_checked;
        $update = $model_cart->editCart($data, array('cart_id'=>array('in',array_values($cart_id))));

        if ($update) {
            $condition = array('buyer_id' => $this->member_info['member_id']);
            $cart_list  = $model_cart->listCart('db', $condition);
            $store_cart_list = $this->_get_cart_info($cart_list);
            output_data($store_cart_list);
        } else {
            output_error('修改失败');
        }
    }


    /**
     * 查询购物车中各个商品,统计购物车中商品数量和总金额
     */
    private function _get_cart_info($cart_list) {
// 购物车列表 [得到最新商品属性及促销信息]
        $logic_buy_1 = logic('buy_1');
        $cart_goods_list = $logic_buy_1->getGoodsCartList($cart_list);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $cart_total = 0;
        $cart_weight = 0;
        $cart_count = 0;
        $bonded_count = 0;   //保税仓商品的数量(勾选及未勾选)
        $non_bonded_count = 0;   //非保税仓商品的数量(勾选及未勾选)
        $cart_all_item = '';
        $no_chain_items = '';
        $cart_all_checked = 1;
        $bonded_checked = 0;  //勾选了保税仓商品:  0否 1是
        $non_bonded_checked = 0;  //勾选了非保税仓商品:   0否 1是
        $has_chain_checked = 0;
        $transport_ids = array();
        $store_cart_list = array();
        foreach ($cart_goods_list as $cart) {
            $cart['goods_image']  = thumb($cart,60);
            $cart['is_bonded']  = $cart['transport_id'] > 5 ? 1:0;
            $cart['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
            $cart['goods_tprice'] = ncPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $cart['goods_tweight'] = ncPriceFormat($cart['goods_weight'] * $cart['goods_num'] /1000);
            $store_cart_list['cart_list'][] = $cart;

            //所有勾选及非勾选的项用逗号分隔
            $cart_all_item .=$cart['cart_id'].',';
            $store_cart_list['cart_all_item'] = $cart_all_item;

            if($cart['transport_id'] > 5){
                $bonded_count++;
            }else{
                $non_bonded_count++;
            }
            $store_cart_list['both_bonded_none'] =  ($bonded_count > 0 && $non_bonded_count) > 0 ? 1:0 ;
            if($cart['cart_checked']){
                //所有勾选项的模板ID组成的array
                $transport_ids[] = $cart['transport_id'];
                if($cart['is_chain'] == 1){
                    $has_chain_checked = 1;
                    $non_bonded_checked =1;
                }else{
                    $no_chain_items .=$cart['cart_id'].',';
                    $store_cart_list['no_chain_items'] = $no_chain_items;
                    //购物车中含有保税仓的商品
                    if($cart['transport_id'] > 5){
                        $bonded_checked =1;
                    }else{
                        $non_bonded_checked =1;
                    }
                }
                $cart_total += $cart['goods_tprice'];
                $store_cart_list['cart_total'] = ncPriceFormat($cart_total);

                $cart_weight += $cart['goods_tweight'];
                $store_cart_list['cart_weight'] = $cart_weight;

                $cart_count++;
                $store_cart_list['cart_count'] = $cart_count;

            }else{
                $cart_all_checked = 0;
            }
        }

        $store_cart_list['cart_all_checked'] = $cart_all_checked;
        //计算运费
        $dregion = $_COOKIE['dregion'];
        $deliver_region = explode('|', $dregion);
        $deliver_region_ids = explode(' ', $deliver_region[0]);
        $freight_total = Model('transport')->weight_area_freight($transport_ids,$deliver_region_ids[2],$cart_weight,$cart_total);
        $store_cart_list['freight_total'] = ncPriceFormat($freight_total);
        $store_cart_list['need2pay'] = ncPriceFormat($cart_total + $freight_total);
        $store_cart_list['bonded_checked'] =  $bonded_checked;
        $store_cart_list['non_bonded_checked'] =  $non_bonded_checked;
        $store_cart_list['has_chain_checked'] =  $has_chain_checked;
        return $store_cart_list;
    }

    private function warehouse_grouped($cart_list) {
// 购物车列表 [得到最新商品属性及促销信息]
        $logic_buy_1 = logic('buy_1');
        $cart_list = $logic_buy_1->getGoodsCartList($cart_list);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $checked_cart_count = 0;
        $store_cart_list = array();
        foreach ($cart_list as $cart) {
            $warehouse[$cart['warehouse']]['transport_ids'][] = $cart['transport_id'];
            $warehouse[$cart['warehouse']]['warehouse_id'] = $cart['warehouse'];
            $cart['goods_image'] = thumb($cart,60);
            $cart['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
            $cart['goods_tprice'] = ncPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $cart['goods_tweight'] = ncPriceFormat($cart['goods_weight'] * $cart['goods_num'] /1000);
            $warehouse[$cart['warehouse']]['cart_goods'][] = $cart;

         //   $cart_all_item .=$cart['cart_id'].',';
          //  $store_cart_list['cart_all_item'] = $cart_all_item;
            if($cart['cart_checked']){
                $checked_cart_count++;
                $warehouse[$cart['warehouse']]['cart_total'] += $cart['goods_tprice'];
                $warehouse[$cart['warehouse']]['goods_tweight'] += $cart['goods_tweight'];
            }
        }
        $cart_all_checked = $checked_cart_count != count($cart_list) ? '1':'0';

        //计算运费
        //$dregion = $_COOKIE['dregion'];
        //$deliver_region = explode('|', $dregion);
        //$deliver_region_ids = explode(' ', $deliver_region[0]);
        //$freight_total = Model('transport')->weight_area_freight($transport_ids,$deliver_region_ids[2],$cart_weight,$cart_total);
        //$store_cart_list['freight_total'] = ncPriceFormat($freight_total);
        //$store_cart_list['need2pay'] = ncPriceFormat($cart_total + $freight_total);
        //$need_idcard_count > 0 ? $store_cart_list['need_idcard'] = 1 : $store_cart_list['need_idcard'] = 0;
        return [$warehouse,$checked_cart_count,$cart_all_checked];
    }


}
