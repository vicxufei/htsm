<?php
/**
 * 前台品牌分类
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
class brandControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function countryOp() {
        $brand_list = Model('brand')->getBrandPassedList(array('brand_recommend' => '0'), 'brand_id,brand_name,brand_pic,brand_country_code,brand_country_en,brand_country_zh');
        if (!empty($brand_list)) {
            foreach ($brand_list as $key => $val) {
                $country =$val['brand_country_code'];
                $brand_country[$country]['country_pic']=STATIC_URL.'/images/country/'.$val['brand_country_code'].'.png';
                $brand_country[$country]['country_code']=$val['brand_country_code'];
                $brand_country[$country]['country_en']=$val['brand_country_en'];
                $brand_country[$country]['country_zh']=$val['brand_country_zh'];
                $brand_id =$val['brand_id'];
                $brand_country[$country]['brand'][$brand_id]=$brand_list[$key];
                $brand_country[$country]['brand'][$brand_id]['brand_pic'] = brandImage($val['brand_pic']);
                //array_splice($brand_country[$country]['brand'][$key],3,4);
                //unset($brand_country[$country]['brand'][$brand_id]['country_pic']);
            }
        }
        output_data(array('brand_list' => $brand_country));
    }

    public function recommend_listOp() {
        $brand_list = Model('brand')->getBrandPassedList(array('brand_recommend' => '1'), 'brand_id,brand_name,brand_pic');
        if (!empty($brand_list)) {
            foreach ($brand_list as $key => $val) {
                $brand_list[$key]['brand_pic'] = brandImage($val['brand_pic']);
            }
        }
        output_data(array('brand_list' => $brand_list));
    }


}
