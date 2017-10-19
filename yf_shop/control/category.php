<?php
/**
 * 前台分类
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');

class categoryControl extends BaseHomeControl {
    /**
     * 分类列表
     */
    public function indexOp(){
        Language::read('home_category_index');
        $lang   = Language::getLangContent();
        //导航
        $nav_link = array(
            '0'=>array('title'=>$lang['homepage'],'link'=>SHOP_SITE_URL),
            '1'=>array('title'=>$lang['category_index_goods_class'])
        );
        Tpl::output('nav_link_list',$nav_link);

        Tpl::output('html_title',C('site_name').' - '.Language::get('category_index_goods_class'));
        //Tpl::showpage('category');
        Tpl::display('category');
    }
}
