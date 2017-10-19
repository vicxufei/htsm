<?php
/**
 * 活动
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');

class activityControl extends BaseSellerControl {
    /**
     * 单个活动信息页
     */
    public function indexOp(){
        $list   = Model('activity_detail')->getGoodsList(array('order'=>'activity_detail.activity_detail_sort asc','activity_id'=>"$activity_id",'goods_show'=>'1','activity_detail_state'=>'1'));
        Tpl::output('list',$list);
        Tpl::output('html_title',C('site_name').' - '.$activity['activity_title']);
        Tpl::showpage('activity_show');
    }
}
