<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/5/8
 * Time: 下午5:57
 */
defined('ByShopWWI') or exit('Access Invalid!');
class yfControl extends mobileHomeControl{
    public function __construct() {
        parent::__construct();
    }

    public function indexOp(){
        $update = ['slide'=>1462847181];
        output_data($update);
    }

    public function slide_homeOp(){
        $json_string = file_get_contents('../dist/json/slide.json');
        $slide = json_decode($json_string, true);
        //$slide = array(
        //    array(
        //        "img-src" => 'http://img2.bbgstatic.com/15483e79372_bc_85ca2a12e12080211cec7dc30e15413d_1920x350.jpeg',
        //        "url" => 'http://www1.htths.com'
        //    ),
        //    array(
        //        "img-src" => 'http://img2.bbgstatic.com/15483e79372_bc_85ca2a12e12080211cec7dc30e15413d_1920x350.jpeg',
        //        "url" => 'http://www.htths.com'
        //    )
        //);
        output_data($slide);
    }

    public function brand_countryOp(){
        $brand_country = array(
            ["id" => 'au',
             "icon" => 'flag-icon-au',
             "title" => '澳洲馆'
            ],
            ["id" => 'kr',
             "icon-img" => 'flag-icon-kr',
             "icon-name" => '韩国馆'
            ],
            ["id" => 'jp',
             "icon-img" => 'flag-icon-jp',
             "icon-name" => '日本馆'
            ],
            ["id" => 'tw',
             "icon-img" => 'flag-icon-tw',
             "icon-name" => '台湾馆'
            ]
        );
        output_data($brand_country);
    }

    public function new_arrivedOp(){
        $brand_country = array(
            ["id" => 'au',
             "icon" => 'flag-icon-au',
             "title" => '澳洲馆'
            ],
            ["id" => 'kr',
             "icon-img" => 'flag-icon-kr',
             "icon-name" => '韩国馆'
            ],
            ["id" => 'jp',
             "icon-img" => 'flag-icon-jp',
             "icon-name" => '日本馆'
            ],
            ["id" => 'tw',
             "icon-img" => 'flag-icon-tw',
             "icon-name" => '台湾馆'
            ]
        );
        output_data($brand_country);
    }
}
