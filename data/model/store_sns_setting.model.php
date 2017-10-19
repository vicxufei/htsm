<?php
/**
 * 店铺动态自动发布
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');

class store_sns_settingModel extends Model {
    public function __construct(){
        parent::__construct('store_sns_setting');
    }

    /**
     * 获取单条动态设置设置信息
     *
     * @param unknown $condition
     * @param string $field
     * @return array
     */
    public function getStoreSnsSettingInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 保存店铺动态设置
     *
     * @param unknown $insert
     * @return boolean
     */
    public function saveStoreSnsSetting($insert, $replace) {
        return $this->insert($insert, $replace);
    }
}
