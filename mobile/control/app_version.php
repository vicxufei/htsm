<?php
/**
 * 文章
 *
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */



defined('ByShopWWI') or exit('Access Invalid!');
class app_versionControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexOp() {
        $this->versionOp();
    }

    protected function versionOp() {
        output_data(array(
            'android' => array(
                'version' => '206',
                'version_name' => '206',
                'content' => 'xdfdf',
                //mktime(hour,minute,second,month,day,year,is_dst)
                'update_time' => mktime(10,19,12,8,30,2016),
                'link' => 'http://a.app.qq.com/o/simple.jsp?pkgname=com.htths.mall'
            ),
            'ios' => array(
                'version' => '206',
                'version_name' => '2.0.6',
                'content' => 'dfgdfhdfg',
                'update_time' => mktime(10,19,12,8,30,2016),
                'link' => 'https://itunes.apple.com/cn/app/tai-hua-suan-shang-cheng/id984977606'
            )

        ));

    }


}
