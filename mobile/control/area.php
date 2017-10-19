<?php
/**
 * 地区
 *
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */



defined('ByShopWWI') or exit('Access Invalid!');
class areaControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexOp() {
        $this->area_listOp();
    }

    /**
     * 地区列表
     */
    public function area_listOp() {
        $area_id = intval($_GET['area_id']);

        $model_area = Model('area');

        $condition = array();
        if($area_id > 0) {
            $condition['area_parent_id'] = $area_id;
        } else {
            $condition['area_deep'] = 1;
        }
        $area_list = $model_area->getAreaList($condition, 'area_id,area_name,area_deep');
        output_data(array('area_list' => $area_list));
    }

    /**
     * 载入门店自提点
     */
    public function chain_listOp() {
        $list = Model('chain')->getChainList(array('store_id'=>1),
            'chain_id,chain_name,area_info,chain_address,chain_phone,chain_opening_hours');
        if(!empty($list)){
            output_data([chain_list=>$list]);
        }
    }

}
