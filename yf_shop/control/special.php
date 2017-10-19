<?php
/**
 * cms专题
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');
class specialControl extends BaseHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','special');
    }

    public function indexOp() {
        $this->special_listOp();
    }

    /**
     * 专题列表
     */
    public function special_listOp() {
        $conition = array();
        $conition['special_state'] = 2;
        $model_special = Model('cms_special');
        $special_list = $model_special->getShopList($conition, 10, 'special_id desc');
        Tpl::output('show_page', $model_special->showpage(2));
        Tpl::output('special_list', $special_list);

        //分类导航
        $nav_link = array(
            0=>array(
                'title'=>Language::get('homepage'),
                'link'=>SHOP_SITE_URL
            ),
            1=>array(
                'title'=>'专题'
            )
        );
        Tpl::output('nav_link_list', $nav_link);
		$seo_param = array();
        $seo_param['name'] = '专题页面';
        $seo_param['key'] = '网店运维www.shopwwi.com专业缔造未来 实力成就梦想 选择多用户商城选择网店运维';
        $seo_param['description'] = '网店运维www.shopwwi.com专业缔造未来 实力成就梦想 选择多用户商城选择网店运维';
        Model('seo')->type('product')->param($seo_param)->show();

        Tpl::showpage('special_list');
    }

    /**
     * 专题详细页
     */
    public function special_detailOp() {
		$special_id = intval($_GET['special_id']);
        $model_special = Model('cms_special');
        $special_detail = $model_special->getonlyOne($_GET['special_id']);
        $special_file = getCMSSpecialHtml($special_id);
		$seo_param = array();
        $seo_param['name'] = $special_detail['special_title'];
        $seo_param['key'] = $special_detail['special_stitle'];
        $seo_param['description'] = $special_detail['special_stitle'];
		 Model('seo')->type('product')->param($seo_param)->show();
        if($special_file) {
            Tpl::output('special_file', $special_file);
            Tpl::output('index_sign', 'special');
            Tpl::showpage('special_detail');
        } else {
            showMessage('专题不存在', '', '', 'error');
        }

    }
}
