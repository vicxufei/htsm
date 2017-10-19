<?php
/**
 * 商品国家模型
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');

class countryModel extends Model {
    public function __construct() {
        parent::__construct('country');
    }

    /**
     * 国家列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getCountryList($condition, $field = '*', $order = 'id desc') {
        return $this->where($condition)->field($field)->order($order)->select();
    }

}
