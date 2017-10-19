<?php
/**
 * 购买流程
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');
class buyControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']){
            redirect(urlLogin('login', 'index', array('ref_url' => request_uri())));
        }
        //验证该会员是否禁止购买
        if(!$_SESSION['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }

    /**
     * 实物商品 购物车、直接购买第一步:选择收获地址和配送方式
     */
    public function buy_step1Op() {
        //虚拟商品购买分流   暂时不考虑虚拟商品购买
        //$this->_buy_branch($_POST);

        $address_list = Model('address')->getAddressList(array('member_id'=>$this->member_info['member_id']));
        $selected_address_id = intval($_POST['address_id']);
        foreach($address_list as $address_item){
            $conditon = $selected_address_id > 0 ? $address_item['address_id'] == $selected_address_id : $address_item['is_default'] == '1';
            if($conditon){
                $selected_area_id = $address_item['area_id'];
                $selected_address_id = $address_item['address_id'];
                $selected_address_info = $address_item;
                break;
            }
        }

        //得到购买数据
        $logic_buy = Logic('buy');
        $result = $logic_buy->buyStep1($selected_area_id,$_POST['cart_id'], $_POST['ifcart'], $_SESSION['member_id'],$this->member_info['orderdiscount'],$this->member_info['level'],$_POST['ifchain']);

        if(!$result['state']) {
            redirect('index.php?act=cart');
            //output_error($result['msg']);
        } else {
            $result = $result['data'];
        }
        //每个商家的商品重量
        //allow_submit为0说明,默认的收货地址不支持配送
        if(!$result['allow_submit']){
            $selected_address_id = 0;
        }
        if($selected_address_id > 0 && $result['need_idcard'] > 0){
            if(!$selected_address_info["idcard_no"]){
                $result['allow_submit'] = 0;
                $result['error_message'] = '请填写身份证号码!';
            }
        }
        Tpl::output('address_list', $address_list);
        Tpl::output('selected_address_id', $selected_address_id);
        Tpl::output('selected_address_info', $selected_address_info);

        Tpl::output('ifcart', $_POST['ifcart']);
        Tpl::output('ifchain', $_POST['ifchain']);
        Tpl::output('store_cart_list', $result['store_cart_list']);
        Tpl::output('store_cart_weight', $result['store_cart_weight']);
        Tpl::output('store_cart_total', $result['store_cart_total']);
        Tpl::output('freight_total', $result['freight_total']);
        Tpl::output('need2pay', $result['need2pay']);                   //需要支付的金额
        Tpl::output('need_idcard', $result['need_idcard']);
        Tpl::output('allow_submit', $result['allow_submit']);
        Tpl::output('error_message', $result['error_message']);

        Tpl::display2('buy');
    }

    public function buyOp() {
        //虚拟商品购买分流   暂时不考虑虚拟商品购买
        //$this->_buy_branch($_POST);

        $address_list = Model('address')->getAddressList(array('member_id'=>$this->member_info['member_id']));
        $selected_address_id = intval($_POST['address_id']);
        foreach($address_list as $address_item){
            $conditon = $selected_address_id > 0 ? $address_item['address_id'] == $selected_address_id : $address_item['is_default'] == '1';
            if($conditon){
                $selected_area_id = $address_item['area_id'];
                $selected_address_id = $address_item['address_id'];
                $selected_address_info = $address_item;
                break;
            }
        }

        //得到购买数据
        $logic_buy = Logic('buy');
        $result = $logic_buy->buyStep1($selected_area_id,$_POST['cart_id'], $_POST['ifcart'], $_SESSION['member_id'],$this->member_info['orderdiscount'],$this->member_info['level'],$_POST['ifchain']);

        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            $result = $result['data'];
            //if(!$result['allow_submit']){
            //    $selected_address_id = 0;
            //}
            if($selected_address_id > 0 && $result['need_idcard'] > 0){
                if(!$selected_address_info["idcard_no"]){
                    $result['allow_submit'] = 0;
                    $result['error_message'] = '请填写身份证号码!';
                }
            }
            output_data($result);
        }
    }

    /**
     * 生成订单
     *
     */
    public function buy_step2Op() {
        $logic_buy = logic('buy');
        $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email'],$this->member_info['orderdiscount'],$this->member_info['level'],$this->member_info['device_id']);
        if(!$result['state']) {
            output_error($result['msg']);
            //showMessage($result['msg'], 'index.php?act=cart', 'html', 'error');
        }

        //转向到商城支付页面
        output_data(array('pay_sn'=>$result['data']['pay_sn']));
    //    redirect('index.php?act=buy&op=pay&pay_sn='.$result['data']['pay_sn']);
    }

    /**
     * 下单时支付页面
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }

        //查询支付单信息
        $model_order= Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']),true);
        if(empty($pay_info)){
            showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        }
        Tpl::output('pay_info',$pay_info);

        //取子订单列表
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','*','','',array(),true);
        if (empty($order_list)) {
            showMessage('未找到需要支付的订单','index.php?act=member_order','html','error');
        }

        //取特殊类订单信息
        $this->_getOrderExtendList($order_list);

        //定义输出数组
        $pay = array();
        //支付提示主信息
        $pay['order_remind'] = '';
        //重新计算支付金额
        $pay['pay_amount_online'] = 0;
        $pay['pay_amount_offline'] = 0;
        //订单总支付金额(不包含货到付款)
        $pay['pay_amount'] = 0;
        //充值卡支付金额(之前支付中止，余额被锁定)
        $pay['payd_rcb_amount'] = 0;
        //预存款支付金额(之前支付中止，余额被锁定)
        $pay['payd_pd_amount'] = 0;
        //还需在线支付金额(之前支付中止，余额被锁定)
        $pay['payd_diff_amount'] = 0;
        //账户可用金额
        $pay['member_pd'] = 0;
        $pay['member_rcb'] = 0;

        $logic_order = Logic('order');

        //计算相关支付金额
        foreach ($order_list as $key => $order_info) {
            if (!in_array($order_info['payment_code'],array('offline','chain'))) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay['pay_amount_online'] += $order_info['order_amount'];
                    $pay['payd_rcb_amount'] += $order_info['rcb_amount'];
                    $pay['payd_pd_amount'] += $order_info['pd_amount'];
                    $pay['payd_diff_amount'] += $order_info['order_amount'] - $order_info['rcb_amount'] - $order_info['pd_amount'];
                }
                $pay['pay_amount'] += $order_info['order_amount'];
            } else {
                $pay['pay_amount_offline'] += $order_info['order_amount'];
            }
            //显示支付方式
            if ($order_info['payment_code'] == 'offline') {
                $order_list[$key]['payment_type'] = '货到付款';
            } elseif ($order_info['payment_code'] == 'chain') {
                $order_list[$key]['payment_type'] = '门店支付';
            } else {
                $order_list[$key]['payment_type'] = '在线支付';
            }
        }
        if ($order_info['chain_id'] && $order_info['payment_code'] == 'chain') {
            $order_list[0]['order_remind'] = '下单成功，请在'.CHAIN_ORDER_PAYPUT_DAY.'日内前往门店提货，逾期订单将自动取消。';
            $flag_chain = 1;
        }

        Tpl::output('order_list',$order_list);

        //如果线上线下支付金额都为0，转到支付成功页
        if (empty($pay['pay_amount_online']) && empty($pay['pay_amount_offline'])) {
            redirect('index.php?act=buy&op=pay_ok&pay_sn='.$pay_sn.'&is_chain='.$flag_chain.'&pay_amount='.ncPriceFormat($pay_amount));
        }

        //是否显示站内余额操作(如果以前没有使用站内余额支付过且非货到付款)
        $pay['if_show_pdrcb_select'] = ($pay['pay_amount_offline'] == 0 && $pay['payd_rcb_amount'] == 0 && $pay['payd_pd_amount'] == 0);

        //输出订单描述
        if (empty($pay['pay_amount_online'])) {
            $pay['order_remind'] = '下单成功，我们会尽快为您发货，请保持电话畅通。';
        } elseif (empty($pay['pay_amount_offline'])) {
            $pay['order_remind'] = '请您在'.(ORDER_AUTO_CANCEL_TIME*60).'分钟内完成支付，逾期订单将自动取消。 ';
        } else {
            $pay['order_remind'] = '部分商品需要在线支付，请您在'.(ORDER_AUTO_CANCEL_TIME*60).'分钟内完成支付，逾期订单将自动取消。';
        }
        if (!empty($order_list[0]['order_remind'])) {
            $pay['order_remind'] = $order_list[0]['order_remind'];
        }

        if ($pay['pay_amount_online'] > 0) {
            //显示支付接口列表
            $model_payment = Model('payment');
            $condition = array();
            $payment_list = $model_payment->getPaymentOpenList($condition);
            if (!empty($payment_list)) {
                unset($payment_list['predeposit']);
                unset($payment_list['offline']);
            }
            if (empty($payment_list)) {
                showMessage('暂未找到合适的支付方式','index.php?act=member_order','html','error');
            }
            Tpl::output('payment_list',$payment_list);
        }
        if ($pay['if_show_pdrcb_select']) {
            //显示预存款、支付密码、充值卡
            $available_predeposit = $available_rc_balance = 0;
            $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
            if (floatval($buyer_info['available_predeposit']) > 0) {
                $pay['member_pd'] = $buyer_info['available_predeposit'];
            }
            if (floatval($buyer_info['available_rc_balance']) > 0) {
                $pay['member_rcb'] = $buyer_info['available_rc_balance'];
            }
            $pay['member_paypwd'] = $buyer_info['member_paypwd'] ? true : false;
        }

        Tpl::output('pay',$pay);

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::display2('pay');
    }

    /**
     * 特殊订单支付最后一步界面展示（目前只有预定）
     * @param unknown $order_list
     */
    private function _getOrderExtendList(& $order_list) {
//        //预定订单

    }

    /**
     * 预存款充值下单时支付页面
     */
    public function pd_payOp() {
        $pay_sn = $_GET['pay_sn'];
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage(Language::get('para_error'),urlMember('predeposit'),'html','error');
        }

        //查询支付单信息
        $model_order= Model('predeposit');
        $pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$_SESSION['member_id']));
        if(empty($pd_info)){
            showMessage(Language::get('para_error'),'','html','error');
        }
        if (intval($pd_info['pdr_payment_state'])) {
            showMessage('您的订单已经支付，请勿重复支付',urlMember('predeposit'),'html','error');
        }
        Tpl::output('pdr_info',$pd_info);

        //显示支付接口列表
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = array('not in',array('offline','predeposit','wxpay'));
        $condition['payment_state'] = 1;
        $payment_list = $model_payment->getPaymentList($condition);
        if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式',urlMember('predeposit'),'html','error');
        }
        Tpl::output('payment_list',$payment_list);

        //标识 购买流程执行第几步
        Tpl::output('buy_step','step3');
        Tpl::showpage('predeposit_pay');
    }

    /**
     * 支付成功页面
     */
    public function pay_okOp() {
        header("location: www.htths.com/index.php?controller=member_order&action=index");
        //$pay_sn = $_GET['pay_sn'];
        //if (!preg_match('/^\d{18}$/',$pay_sn)){
        //    showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        //}
        //
        ////查询支付单信息
        //$model_order= Model('order');
        //$pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$_SESSION['member_id']));
        //if(empty($pay_info)){
        //    showMessage(Language::get('cart_order_pay_not_exists'),'index.php?act=member_order','html','error');
        //}
        //Tpl::output('pay_info',$pay_info);
        //
        //Tpl::output('buy_step','step4');
        //Tpl::showpage('buy_step3');
    }

    /**
     * 加载买家收货地址
     *
     */
    public function load_addrOp() {
        $model_addr = Model('address');
        //如果传入ID，先删除再查询
        if (!empty($_GET['id']) && intval($_GET['id']) > 0) {
            $model_addr->delAddress(array('address_id'=>intval($_GET['id']),'member_id'=>$_SESSION['member_id']));
        }
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        if (!C('delivery_isuse')) {
            $condition['dlyp_id'] = 0;
            $order = 'dlyp_id asc,address_id desc';
        }
        $list = $model_addr->getAddressList($condition,$order);
        output_data(array('address_list'=>$list));
      //  Tpl::output('address_list',$list);
      //  Tpl::display('buy_address.load','null_layout');
    }

    /**
     * 载入门店自提点
     */
    public function load_chainOp() {
        $list = Model('chain')->getChainList(array('area_id'=>intval($_GET['area_id']),'store_id'=>intval($_GET['store_id'])),
                'chain_id,chain_name,area_info,chain_address');
        echo $_GET['callback'].'('.json_encode($list).')';
    }

    /**
     * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
     * 如果店铺统一设置了满免运费规则，则运费模板无效
     * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
     * 如果未找到运费模板，按免运费处理
     * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
     */
    public function change_addrOp() {
        $logic_buy = Logic('buy');
        if (empty($_POST['city_id'])) {
            $_POST['city_id'] = $_POST['area_id'];
        }

        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $_SESSION['member_id']);
        if(!empty($data)) {
            exit(json_encode($data));
        } else {
            exit('error');
        }
    }

    //根据门店自提站ID计算商品库存
    public function change_chainOp() {
        $logic_buy = Logic('buy');
        $data = $logic_buy->changeChain($_POST['chain_id'],$_POST['product']);
        if(!empty($data)) {
            exit(json_encode($data));
        } else {
            exit('error');
        }
    }

     /**
      * 添加新的收货地址
      *
      */
     public function add_addrOp(){
        $model_addr = Model('address');
        if (chksubmit()){
            //验证表单信息
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["true_name"],"require"=>"true","message"=>Language::get('cart_step1_input_receiver')),
                array("input"=>$_POST["mob_phone"],"require"=>"true","message"=>'手机号必填'),
                array("input"=>$_POST["area_id"],"require"=>"true","validator"=>"Number","message"=>Language::get('cart_step1_choose_area'))
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                $error = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($error) : $error;
                exit(json_encode(array('state'=>false,'msg'=>$error)));
            }


            $data = array();
            $data['member_id'] = $_SESSION['member_id'];
            $data['true_name'] = $_POST['true_name'];
            $data['area_id'] = intval($_POST['area_id']);
            $data['city_id'] = intval($_POST['city_id']);
            $data['pr_id'] = intval($_POST['pr_id']);
            $data['area_info'] = $_POST['area_info'];
            $data['address'] = $_POST['address'];
            $data['mob_phone'] = $_POST['mob_phone'];
            $data['idcard_no'] = $_POST['idcard_no'];
            $data['is_default'] = $_POST['is_default'] == 'on' ? '1':'0';
            if (intval($_POST['address_id']) > 0){
                $rs = $model_addr->editAddress($data, array('address_id' => intval($_POST['address_id']),'member_id'=>$_SESSION['member_id']));
                if (!$rs){
                    output_error('修改失败!');
                }else{
                    $condition = array();
                    $condition['member_id'] = $_SESSION['member_id'];
                    $order = 'address_id desc';
                    $list = $model_addr->getAddressList($condition,$order);
                    output_data(array('address_list'=>$list));
                }
            }else{
                $count = $model_addr->getAddressCount(array('member_id'=>$_SESSION['member_id']));
                if ($count >= 10) {
                    output_error('最多允许添加10个有效地址');
                }
                $insert_id = $model_addr->addAddress($data);
                if ($insert_id){
                    $condition = array();
                    $condition['member_id'] = $_SESSION['member_id'];
                    if (!C('delivery_isuse')) {
                        $condition['dlyp_id'] = 0;
                        $order = 'dlyp_id asc,address_id desc';
                    }
                    $list = $model_addr->getAddressList($condition,$order);
                   // output_data(array('address_list'=>$list,'selected_address_id'=>$insert_id));
                    output_data(array('address_list'=>$list));
                }else {
                    output_error('新地址添加失败');
                }
            }

        } else {
          //  Tpl::display('buy_address.add','null_layout');
            output_error('表单提交出错');
        }
     }

    public function idcard_editOp(){
        $model_addr = Model('address');
            $data = array();
            $data['member_id'] = $_SESSION['member_id'];
            $data['idcard_no'] = $_POST['idcard_no'];
            if (intval($_POST['address_id']) > 0){
                $rs = $model_addr->editAddress($data, array('address_id' => intval($_POST['address_id']),'member_id'=>$_SESSION['member_id']));
                if (!$rs){
                    output_error('修改失败,请重试!');
                }else{
                    output_data(array('idcard_no'=>$_POST['idcard_no']));
                }
            }else{
                output_error('收货地址编号为空!');
            }


    }




    ///**
    // * 编辑地址
    // */
    //public function address_editOp() {
    //    $address_id = intval($_POST['address_id']);
    //
    //    $model_address = Model('address');
    //
    //    $address_info = $this->_address_valid();
    //    $member_id = $this->member_info['member_id'];
    //    if($address_info['is_default'] == NULL){
    //        $address_count = $model_address->getAddressCount(array('member_id' => $member_id));
    //        $address_count > 0 ? $address_info['is_default']='0' : $address_info['is_default']='1';
    //    }
    //    if($address_info['is_default'] == '1'){
    //        $ret=$model_address->unsetDefaultAddress($member_id);
    //        if(!$ret){output_error('设置为非默认地址失败');}
    //    }
    //
    //    //验证地址是否为本人
    //    $address_info = $model_address->getOneAddress($address_id);
    //    if ($address_info['member_id'] != $this->member_info['member_id']) {
    //        output_error('参数错误');
    //    }
    //
    //    $address_info = $this->_address_valid();
    //    $result = $model_address->editAddress($address_info, array('address_id' => $address_id));
    //    if($result) {
    //        output_data(array('address_id'=>$address_id));
    //    } else {
    //        output_error('保存失败');
    //    }
    //}

     /**
      * 添加新的门店自提点
      *
      */
     public function add_chainOp(){
         Tpl::showpage('buy_address.add_chain','null_layout');
     }

    /**
     * 加载买家发票列表，最多显示10条
     *
     */
    public function load_invOp() {
        $logic_buy = Logic('buy');

        $condition = array();
        if ($logic_buy->buyDecrypt($_GET['vat_hash'], $_SESSION['member_id']) == 'allow_vat') {
        } else {
            Tpl::output('vat_deny',true);
            $condition['inv_state'] = 1;
        }
        $condition['member_id'] = $_SESSION['member_id'];

        $model_inv = Model('invoice');
        //如果传入ID，先删除再查询
        if (intval($_GET['del_id']) > 0) {
            $model_inv->delInv(array('inv_id'=>intval($_GET['del_id']),'member_id'=>$_SESSION['member_id']));
        }
        $list = $model_inv->getInvList($condition,10);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
               if ($value['inv_state'] == 1) {
                   $list[$key]['content'] = '普通发票'.' '.$value['inv_title'].' '.$value['inv_content'];
               } else {
                   $list[$key]['content'] = '增值税发票'.' '.$value['inv_company'].' '.$value['inv_code'].' '.$value['inv_reg_addr'];
               }
            }
        }
        Tpl::output('inv_list',$list);
        Tpl::display('buy_invoice.load','null_layout');
    }

     /**
      * 新增发票信息
      *
      */
     public function add_invOp(){
        $model_inv = Model('invoice');
        if (chksubmit()){
            //如果是增值税发票验证表单信息
            if ($_POST['invoice_type'] == 2) {
                if (empty($_POST['inv_company']) || empty($_POST['inv_code']) || empty($_POST['inv_reg_addr'])) {
                    exit(json_encode(array('state'=>false,'msg'=>Language::get('nc_common_save_fail','UTF-8'))));
                }
            }
            $data = array();
            if ($_POST['invoice_type'] == 1) {
                $data['inv_state'] = 1;
                $data['inv_title'] = $_POST['inv_title_select'] == 'person' ? '个人' : $_POST['inv_title'];
                $data['inv_content'] = $_POST['inv_content'];
            } else {
                $data['inv_state'] = 2;
                $data['inv_company'] = $_POST['inv_company'];
                $data['inv_code'] = $_POST['inv_code'];
                $data['inv_reg_addr'] = $_POST['inv_reg_addr'];
                $data['inv_reg_phone'] = $_POST['inv_reg_phone'];
                $data['inv_reg_bname'] = $_POST['inv_reg_bname'];
                $data['inv_reg_baccount'] = $_POST['inv_reg_baccount'];
                $data['inv_rec_name'] = $_POST['inv_rec_name'];
                $data['inv_rec_mobphone'] = $_POST['inv_rec_mobphone'];
                $data['inv_rec_province'] = $_POST['vregion'];
                $data['inv_goto_addr'] = $_POST['inv_goto_addr'];
            }
            $data['member_id'] = $_SESSION['member_id'];
            //转码
            $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
            $insert_id = $model_inv->addInv($data);
            if ($insert_id) {
                exit(json_encode(array('state'=>'success','id'=>$insert_id)));
            } else {
                exit(json_encode(array('state'=>'fail','msg'=>Language::get('nc_common_save_fail','UTF-8'))));
            }
        } else {
            Tpl::display('buy_address.add','null_layout');
        }
     }

    /**
     * AJAX验证支付密码
     */
    public function check_pd_pwdOp(){
        if (empty($_GET['password'])) exit('0');
        $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_paypwd');
        echo ($buyer_info['member_paypwd'] != '' && $buyer_info['member_paypwd'] === md5($_GET['password'])) ? '1' : '0';
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }
        return $buy_items;
    }

    /**
     * 购买分流
     */
    private function _buy_branch($post) {
        if (!$post['ifcart']) {
            //取得购买商品信息
            $buy_items = $this->_parseItems($post['cart_id']);
            $goods_id = key($buy_items);
            $quantity = current($buy_items);

            $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);
            if ($goods_info['is_virtual']) {
                redirect('index.php?act=buy_virtual&op=buy_step1&goods_id='.$goods_id.'&quantity='.$quantity);
            }
        }
    }

}
