<?php
/**
 * 商品评价
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */



defined('ByShopWWI') or exit('Access Invalid!');

class member_evaluateControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 评价列表
     */
    public function listOp(){
        $model_evaluate_goods = Model('evaluate_goods');

        $condition = array();
        $condition['geval_frommemberid'] = $this->member_info['member_id'];

        $pagesize=intval($_POST['page']);
        if($pagesize == 0){
            $pagesize = 5;
        }
        if (is_numeric($_POST['curpage']) && $_POST['curpage'] > 0) {
            $curpage = intval($_POST['curpage']);
            $start =  ($curpage-1) * $pagesize;
        } else {
            $start = 0;
        }
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, $pagesize, 'geval_id desc',"$start,$pagesize",'geval_orderid,geval_orderno,geval_ordergoodsid,geval_goodsid,geval_goodsname,geval_goodsprice,geval_goodsimage,geval_scores,geval_content,geval_isanonymous,geval_addtime,geval_explain');
        foreach ($goodsevallist as $key=>$goodseval_item){
            $goodsevallist[$key]['goods_image_url'] = yf_cthumb2($goodseval_item['geval_goodsimage']);
            unset($goodsevallist[$key]['geval_goodsimage']);
        }
        $page_count = $model_evaluate_goods->gettotalpage();
        output_data(['goodsevallist'=>$goodsevallist],mobile_page($page_count));
       // Tpl::output('goodsevallist',$goodsevallist);
        //Tpl::output('show_page',$model_evaluate_goods->showpage());

    }
    /**
     * 订单添加评价
     */
    public function addOp(){
        $evaluations_array = explode(',',$_POST['evaluations']);

        $evaluate_items = array();
        if (is_array($evaluations_array)) {
            foreach ($evaluations_array as $value) {
                $evaluate_item_array = explode('|',$value);
                $id = trim($evaluate_item_array[0],'[&quot;');
                $evaluate_items[$id]['score']= intval($evaluate_item_array[1]);
                $evaluate_items[$id]['comment']= $evaluate_item_array[2];
                $evaluate_items[$id]['anony']= intval($evaluate_item_array[3]);
            }
        }

        $order_id = intval($_POST['order_id']);
        if (!$order_id){
            output_error('参数错误');
        }

        $model_order = Model('order');
        //$model_store = Model('store');
        $model_evaluate_goods = Model('evaluate_goods');
       // $model_evaluate_store = Model('evaluate_store');

        //获取订单信息
        $order_info = $model_order->getOrderInfo(array('order_id' => $order_id));
        //判断订单身份
        if($order_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('非法操作!');
        }
        //订单为'已收货'状态，并且未评论
        $order_info['evaluate_able'] = $model_order->getOrderOperateState('evaluation',$order_info);
        if (empty($order_info) || !$order_info['evaluate_able']){
            output_error('订单信息错误!');
        }

        //获取订单商品
        $order_goods = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
        if(empty($order_goods)){
            output_error('订单信息错误!');
        }

        $evaluate_goods_array = array();
        $goodsid_array = array();
        foreach ($order_goods as $value){
            //如果未评分，默认为5分
            //$evaluate_score = intval($_POST['goods'][$value['rec_id']]['score']);
            $evaluate_score = intval($evaluate_items[$value['rec_id']]['score']);
            if($evaluate_score <= 0 || $evaluate_score > 5) {
                $evaluate_score = 5;
            }
            //默认评语
            //$evaluate_comment = $_POST['goods'][$value['rec_id']]['comment'];
            $evaluate_comment = $evaluate_items[$value['rec_id']]['comment'];
            if(empty($evaluate_comment)) {
                $evaluate_comment = '不错哦';
            }

            //$geval_image = '';
            //if (isset($_POST['goods'][$value['rec_id']]['evaluate_image']) && is_array($_POST['goods'][$value['rec_id']]['evaluate_image'])) {
            //    foreach ($_POST['goods'][$value['rec_id']]['evaluate_image'] as $val) {
            //        if(!empty($val)) {
            //            $geval_image .= $val . ',';
            //        }
            //    }
            //}
            //$geval_image = rtrim($geval_image, ',');

            $evaluate_goods_info = array();
            $evaluate_goods_info['geval_orderid'] = $order_id;
            $evaluate_goods_info['geval_orderno'] = $order_info['order_sn'];
            $evaluate_goods_info['geval_ordergoodsid'] = $value['rec_id'];
            $evaluate_goods_info['geval_goodsid'] = $value['goods_id'];
            $evaluate_goods_info['geval_goodsname'] = $value['goods_name'];
            $evaluate_goods_info['geval_goodsprice'] = $value['goods_price'];
            $evaluate_goods_info['geval_goodsimage'] = $value['goods_image'];
            $evaluate_goods_info['geval_scores'] = $evaluate_score;
            $evaluate_goods_info['geval_content'] = $evaluate_comment;
            //$evaluate_goods_info['geval_isanonymous'] = $_POST['goods'][$value['rec_id']]['anony']?1:0;
            $evaluate_goods_info['geval_isanonymous'] = $evaluate_items[$value['rec_id']]['anony']?1:0;
            $evaluate_goods_info['geval_addtime'] = TIMESTAMP;
            //$evaluate_goods_info['geval_storeid'] = $store_info['store_id'];
            //$evaluate_goods_info['geval_storename'] = $store_info['store_name'];
            $evaluate_goods_info['geval_frommemberid'] = $this->member_info['member_id'];
            $evaluate_goods_info['geval_frommembername'] = $this->member_info['member_name'];
            //$evaluate_goods_info['geval_image'] = $geval_image;
            $evaluate_goods_info['geval_content_again'] = '';
            $evaluate_goods_info['geval_image_again'] = '';
            $evaluate_goods_info['geval_explain_again'] = '';

            $evaluate_goods_array[] = $evaluate_goods_info;

            $goodsid_array[] = $value['goods_id'];
        }

        $model_evaluate_goods->addEvaluateGoodsArray($evaluate_goods_array, $goodsid_array);

        //更新订单信息并记录订单日志
        $state = $model_order->editOrder(array('evaluation_state'=>1), array('order_id' => $order_id));
        $model_order->editOrderCommon(array('evaluation_time'=>TIMESTAMP), array('order_id' => $order_id));
        if ($state){
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = 'buyer';
            //$data['log_msg'] = L('order_log_eval');
            $data['log_msg'] = '评价交易';
            $model_order->addOrderLog($data);
        }

        //添加会员积分
        if (C('points_isuse') == 1){
            $points_model = Model('points');
            $points_model->savePointsLog('comments',array('pl_memberid'=>$this->member_info['member_id'],'pl_membername'=>$this->member_info['member_name']));
        }
        //添加会员经验值
        Model('exppoints')->saveExppointsLog('comments',array('exp_memberid'=>$this->member_info['member_id'],'exp_membername'=>$this->member_info['member_name']));;
        //showDialog(Language::get('member_evaluation_evaluat_success'),'index.php?act=member_order', 'succ');
        output_data('评论成功!');
    }

}
