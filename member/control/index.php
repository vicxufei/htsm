<?php
/**
 * 默认展示页面
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');
class indexControl extends BaseLoginControl{
    public function __construct() {
        @header("location: " . urlMember('member_information'));
    }
}
