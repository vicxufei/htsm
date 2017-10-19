<?php
/**
 * 手机端首页控制
 *
 *by wansyb QQ群：111731672
 *你正在使用的是由网店 运 维提供S2.0系统！保障你的网络安全！ 购买授权请前往shopnc
 */



defined('ByShopWWI') or exit('Access Invalid!');
class indexControl extends mobileHomeControl{

    private $slide_update_time;
    private $icon_update_time;
    private $gongao_upate_time;
    private $activity_update_time;
    private $goods_class_update_time;
    private $country_class_update_time;

    public function __construct() {
        parent::__construct();
        //mktime(hour,minute,second,month,day,year,is_dst)
        $this->slide_update_time = mktime(19,20,12,12,17,2017);
        $this->icon_update_time = mktime(8,19,11,11,12,2017);
        $this->gongao_upate_time = mktime(23,20,11,11,16,2017);
        $this->activity_update_time = mktime(31,26,14,8,28,2017);
        $this->goods_class_update_time = mktime(14,32,11,7,5,2017);
        $this->country_class_update_time = mktime(11,45,10,6,20,2016);
    }

    /**
     * 首页
     */
    public function indexOp() {
        //$model_mb_special = Model('mb_special');
        //$data = $model_mb_special->getMbSpecialIndex();
        //$this->_output_special($data, $_GET['type']);
        output_data(['slide'=>$this->slide_update_time,
                     'icon'=>$this->icon_update_time,
                     'gongao'=>$this->gongao_upate_time,
                     'activity'=>$this->activity_update_time,
                     'goods_class'=>$this->goods_class_update_time,
                     'country_class'=>$this->country_class_update_time
            ]);
    }

    //public function timestamp(){
    //    output_data(['slide_timestamp'=>$this->slide_update_time,'icon_timestamp'=>$this->icon_update_time]);
    //}

    public function slideOp() {
        $slide = array(
          array(
                "img" => 'http://img2.htths.com/mslide/ziti.jpg',
                "type" => 'wap',
                "data" => 'http://m.htths.com/ziti.html'
            ),
          array(
            "img" => 'http://img2.htths.com/mslide/xyc.jpg',
            "type" => 'goods',
            "data" => ['keyword'=>'西域春']
          ),
//            array(
//                "img" => 'http://img2.htths.com/mslide/oppo.jpg',
//                "type" => 'goods',
//                "data" => ['keyword'=>'oppo']
//            ),
            array(
                "img" => 'http://img2.htths.com/mslide/slide13.jpg',
                "type" => 'good',
                "data" => 103399
            ),
            array(
                "img" => 'http://img2.htths.com/mslide/slide16.jpg',
                "type" => 'good',
                "data" => 102764
            ),

            //array(
            //    "img" => 'http://img2.htths.com/mslide/slide3.jpg',
            //    "type" => 'wap',
            //    "data" => 'http://www.htths.com/app-download.html'
            //),
            //array(
            //    "img" => 'http://img2.htths.com/mslide/slide6.jpg',
            //    "type" => 'good',
            //    "data" => 102756
            //),
            //array(
            //    "img" => 'http://img2.htths.com/mslide/slide5.jpg',
            //    "type" => 'goods',
            //    "data" => ['cate_id'=>'2401']
            //)
            //array(
            //    "img" => 'http://img1.bbgstatic.com/154bda29c62_bc_cbb1721cd054350cca5aebd030c5858c_1242x513.jpeg',
            //    "type" => 'goods',
            //    "data" => ['b_id'=>'567']
            //),
            //array(
            //    "img" => 'http://img4.bbgstatic.com/154c2942acb_bc_a5fd18942f216a9b0845f7191f2c11b7_1242x513.jpeg',
            //    "type" => 'goods',
            //    "data" => ['keyword'=>'花王']
            //),
            //array(
            //    "img" => 'http://img4.bbgstatic.com/154c2942acb_bc_a5fd18942f216a9b0845f7191f2c11b7_1242x513.jpeg',
            //    "type" => 'wap',
            //    "data" => 'http://m.htths.com'
            //),
            //array(
            //    "img" => 'http://img4.bbgstatic.com/154c2942acb_bc_a5fd18942f216a9b0845f7191f2c11b7_1242x513.jpeg',
            //    "type" => 'page',
            //    "data" => ['title'=>'文章标题','content'=>json_encode('<p>图文的html</p>')]
            //)
        );
        output_data(['timestamp'=>$this->slide_update_time,'slide'=>$slide]);
    }
    public function activityOp() {
        $activity = array(
//          array(
//            "img" => 'http://img2.htths.com/mslide/xinshijie.jpeg',
//            "type" => 'wap',
//            "data" => 'http://a2.rabbitpre.com/m/IRuiryVB5/'
//          ),
            array(
                "img" => 'http://img2.htths.com/mactivity/laiyifen.jpg',
                "type" => 'goods',
                "data" => ['keyword'=>'来伊份']
            ),
            //array(
            //    "img" => 'http://img0.bbgstatic.com/154c38698e7_bc_bfbbfa9a06e3c9d7d6d76bb0684bae41_1242x600.jpeg',
            //    "type" => 'goods',
            //    "data" => ['cate_id'=>'3300']
            //),
            //array(
            //    "img" => 'http://img1.bbgstatic.com/154bda29c62_bc_cbb1721cd054350cca5aebd030c5858c_1242x513.jpeg',
            //    "type" => 'goods',
            //    "data" => ['b_id'=>'567']
            //),
            //array(
            //    "img" => 'http://img2.htths.com/mactivity/activity10.jpg',
            //    "type" => 'goods',
            //    "data" => ['keyword'=>'月饼']
            //)
//            array(
//                "img" => 'http://img2.htths.com/mactivity/38.jpg',
//                "type" => 'goods',
//                "data" => ['keyword'=>'情人节']
//            )
        );
        output_data(['timestamp'=>$this->activity_update_time,'activity'=>$activity]);
    }

    public function activity2Op() {
        $activity = array(
            //array(
            //    "img" => 'http://img2.htths.com/mactivity/activity9.jpg',
            //    "type" => 'good',
            //    "data" => 103244
            //)
            //array(
            //    "img" => 'http://img0.bbgstatic.com/154c38698e7_bc_bfbbfa9a06e3c9d7d6d76bb0684bae41_1242x600.jpeg',
            //    "type" => 'goods',
            //    "data" => ['cate_id'=>'3300']
            //),
            //array(
            //    "img" => 'http://img1.bbgstatic.com/154bda29c62_bc_cbb1721cd054350cca5aebd030c5858c_1242x513.jpeg',
            //    "type" => 'goods',
            //    "data" => ['b_id'=>'567']
            //),
            array(
                "img" => 'http://img2.htths.com/mactivity/activity10.jpg',
                "type" => 'goods',
                "data" => ['keyword'=>'月饼']
            )
        );
//        $model_mb_user_token = Model('mb_user_token');
//        $key = $_POST['key'];
//        if(empty($key)) {
//            $key = $_GET['key'];
//        }
//        $version = array('010.0.07','01.00.07');
//        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
//        if(!empty($mb_user_token_info) && !in_array($mb_user_token_info['app_version'],$version)) {
//            $activity[] = array(
//                "img" => 'http://img2.htths.com/mactivity/activity10.jpg',
//                "type" => 'goods',
//                "data" => ['keyword'=>'月饼']
//            );
//        }

        output_data(['timestamp'=>$this->activity_update_time,'activity'=>$activity]);
    }

    public function gongaoOp(){
        $mobile_os = $_POST['mobile_os'];
        $app_version = $_POST['version'];
        $text = '';
        $banners = '';
//        if(!in_array($app_version,['2.0.15'])){
//            $text = array(
//                "type"=> 'wap',
//                "data"=> 'http://a.app.qq.com/o/simple.jsp?pkgname=com.htths.mall',
//                "text" => '太划算商城App发布了新版本V2.0.15,请点击升级!'
//            );
//            $banners = [
//                array(
//                    "img" => 'http://static.htths.com/images/app_update.jpg',
//                    "type" => 'wap',
//                    "data" => 'http://a.app.qq.com/o/simple.jsp?pkgname=com.htths.mall'
//                )
//            ];
//        }

//        $text = array(
//            "type"=> 'wap',
//            "data"=> '',
//            "text" => '1月20日快递只发江苏、浙江、上海。1月21日起只接单不发货。2月4日(初八)开始江浙沪皖等地正常发货，部分外围地区可能还会延迟发货！'
//        );
//        output_data(['timestamp'=>$this->gongao_upate_time,'text'=>$text,'banners'=>$banners,'mobile_os'=>$mobile_os,'app_version'=>$app_version]);
        output_data(['timestamp'=>$this->gongao_upate_time,'text'=>$text,'banners'=>$banners]);
    }

    public function iconOp() {
        $icon = [
            [
              //'img' => 'http://img2.htths.com/micon/techan.png',
              //'img' => 'http://img2.htths.com/micon/hui.png',
              'img' => 'http://img2.htths.com/micon/miaosha.jpg',
              'text' => '秒杀',
              "type" => 'goods',
              "data" => ['keyword'=>'秒杀']
            ],
            [
                'img' => 'http://img2.htths.com/micon/shuiguo.png',
                'text' => '水果',
                "type" => 'goods',
                "data" => ['cate_id'=>'1100']
            ],
//            [
//                'img' => 'http://img2.htths.com/micon/new.png',
//                'text' => '新品',
//                "type" => 'goods',
//                "data" => ['key'=>'4']
//            ],
            [
                'img' => 'http://img2.htths.com/micon/quanqiu.png',
                'text' => '全球购',
                "type" => 'goods',
                "data" => ['keyword'=>'保税仓']
            ],
            [
                //'img' => 'http://img2.htths.com/micon/techan.png',
                'img' => 'http://img2.htths.com/micon/hui.png',
                //'img' => 'http://img2.htths.com/micon/miaosha.jpg',
                'text' => 'APP专享',
                "type" => 'goods',
                "data" => ['keyword'=>'专享']
            ],
//            [
//              'img' => 'http://img2.htths.com/micon/map.png',
//              'text' => '自提网点',
//              "type" => 'wap',
//              "data" => ['keyword'=>'专享']
//            ],

            //[
            //    //'img' => 'http://img2.htths.com/micon/techan.png',
            //    'img' => 'http://img2.htths.com/micon/hui.png',
            //    'text' => '秒杀',
            //    "type" => 'goods',
            //    "data" => ['keyword'=>'专享']
            //],
            //[
            //    'img' => 'http://img2.htths.com/micon/qiche.png',
            //    'text' => '汽车养护',
            //    "type" => 'goods',
            //    "data" => ['cate_id'=>'8156']
            //]

            //[
            //    'img' => 'http://img2.bbgstatic.com/153f017e11f_bc_9e7bbc64b475ab666e53ffa7e9530d32_126x126.png',
            //    'text' => '澳洲馆',
            //    "type" => 'goods',
            //    "data" => ['c_code'=>'au']
            //]

        ];
        output_data(['timestamp'=>$this->icon_update_time,'icon'=>$icon]);
    }

    public function xianshiOp() {
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_array = $model_xianshi_goods->getXianshiGoodsCommendList(24);
        $xianshi = [];
        if(is_array($xianshi_array)){
            foreach($xianshi_array as $key=>$value){
                $xianshi[$key]['goods_id'] = $value['goods_id'];
                $xianshi[$key]['goods_name'] = $value['goods_name'];
                $xianshi[$key]['goods_price'] = $value['goods_price'];
                $xianshi[$key]['xianshi_price'] = $value['xianshi_price'];
                $xianshi[$key]['goods_image_url'] = yf_cthumb2($value['goods_image'],'100');
                $xianshi[$key]['state'] = $value['state'];
                $xianshi[$key]['state_text'] = $value['state'] ? '进行中':'即将开始';
                $xianshi[$key]['start_time'] = $value['start_time'];
                $xianshi[$key]['end_time'] = date('Y-m-d H:i:s',$value['end_time']);
                $xianshi[$key]['end_time2'] = $value['end_time'];
                $xianshi[$key]['lower_limit'] = $value['lower_limit'];
                $xianshi[$key]['upper_limit'] = 0;
                $xianshi[$key]['xianshi_discount'] = $value['xianshi_discount'];
            }
        }
        output_data(array('xianshi'=>$xianshi));
    }
    public function xianshi2Op() {
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_array = $model_xianshi_goods->getXianshiGoodsCommendList(24);
        $xianshi = array();
        if(is_array($xianshi_array)){
            foreach($xianshi_array as $key=>$value){
                $xianshi_item = array();
                $xianshi_item['goods_id'] = $value['goods_id'];
                $xianshi_item['goods_name'] = $value['goods_name'];
                $xianshi_item['goods_price'] = $value['goods_marketprice'];
                $xianshi_item['xianshi_price'] = $value['xianshi_price'];
                $xianshi_item['goods_image_url'] = yf_cthumb2($value['goods_image'],'100');
                //$xianshi_item[$key]['state'] = $value['state'];
                //$xianshi_item[$key]['state_text'] = $value['state'] ? '进行中':'即将开始';
                //$xianshi_item[$key]['start_time'] = $value['start_time'];
                //$xianshi_item[$key]['end_time'] = date('Y-m-d H:i:s',$value['end_time']);
                //$xianshi_item[$key]['end_time2'] = $value['end_time'];
                //$xianshi_item[$key]['lower_limit'] = $value['lower_limit'];
                //$xianshi_item[$key]['upper_limit'] = 0;
                $xianshi_item['xianshi_discount'] = $value['xianshi_discount'];
                $xianshi[$value['end_time']]['title'] = $value['xianshi_title'];
                $xianshi[$value['end_time']]['endtime'] = $value['end_time'] - time() > 3600*24 ? '' : date('Y-m-d H:i:s',$value['end_time']);
                //$xianshi[$value['end_time']]['endtime'] = date('Y-m-d H:i:s',$value['end_time']);
                $xianshi[$value['end_time']]['goods'][] = $xianshi_item;
            }


        }
        output_data(array('xianshi'=>$xianshi));
    }

    public function xianshi3Op() {
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_array = $model_xianshi_goods->getXianshiGoodsCommendList2(24);
        $xianshi = array();
        if(is_array($xianshi_array)){
            foreach($xianshi_array as $key=>$value){
                $xianshi_item = array();
                $xianshi_item['goods_id'] = $value['goods_id'];
                $xianshi_item['goods_name'] = $value['goods_name'];
//                $xianshi_item['goods_price'] = $value['goods_price'];
                $xianshi_item['goods_price'] = $value['goods_marketprice'];
                $xianshi_item['xianshi_price'] = $value['xianshi_price'];
                $xianshi_item['goods_image_url'] = yf_cthumb2($value['goods_image'],'100');
                $xianshi_item['xianshi_discount'] = $value['xianshi_discount'];
                $xianshi[$value['end_time']]['title'] = $value['xianshi_title'];
                //$xianshi[$value['end_time']]['starttime'] = date('Y-m-d H:i:s',$value['start_time']);
                //$xianshi[$value['end_time']]['endtime'] = $value['end_time'] - time() > 3600*24 ? '' : date('Y-m-d H:i:s',$value['end_time']);
                if($value['start_time']- TIMESTAMP > 0){
                    $xianshi[$value['end_time']]['count_down'] = date('Y-m-d H:i:s',$value['start_time']);
                    $xianshi[$value['end_time']]['count_down_text'] = '距开始';
                }else if($value['end_time'] - time() < 3600*24){
                    $xianshi[$value['end_time']]['count_down'] = date('Y-m-d H:i:s',$value['end_time']);
                    $xianshi[$value['end_time']]['count_down_text'] = '距结束';
                }else{
                    $xianshi[$value['end_time']]['count_down'] = '';
                    $xianshi[$value['end_time']]['count_down_text'] = '';
                }
                $xianshi[$value['end_time']]['goods'][] = $xianshi_item;
            }


        }
        output_data(array('xianshi'=>$xianshi));
    }


    public function miaoshaOp() {
        $model_xianshi = Model('p_xianshi');
        $miaosha = $model_xianshi->getXianshiInfoByID(3);
        if($miaosha){
            $miaosha['end_time'] = date('Y-m-d H:i:s',$miaosha['end_time']);
        }
        output_data($miaosha);
    }

    /**
     * 专题
     */
    public function specialOp() {
        $model_mb_special = Model('mb_special');
        $data = $model_mb_special->getMbSpecialItemUsableListByID($_GET['special_id']);
        $this->_output_special($data, $_GET['type'], $_GET['special_id']);
    }

    /**
     * 输出专题
     */
    private function _output_special($data, $type = 'json', $special_id = 0) {
        $model_special = Model('mb_special');
        if($_GET['type'] == 'html') {
            $html_path = $model_special->getMbSpecialHtmlPath($special_id);
            if(!is_file($html_path)) {
                ob_start();
                Tpl::output('list', $data);
                Tpl::showpage('mb_special');
                file_put_contents($html_path, ob_get_clean());
            }
            header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
            die;
        } else {
            output_data($data);
        }
    }
    /**
     * 默认搜索词列表
     */
    public function search_key_listOp() {
		//热门搜索
        $list = @explode(',',C('hot_search'));
        if (!$list || !is_array($list)) { 
            $list = array();
        }
                
        //历史搜索
        if (cookie('his_sh') != '') {
            $his_search_list = explode('~', cookie('his_sh'));
        }

        $data['list'] = $list;
		$data['his_list'] = is_array($his_search_list) ? $his_search_list : array();
		output_data($data);
    }
	
    /**
     * 热门搜索列表
     */
    public function search_hot_infoOp() {
				//热门搜索
        if (C('rec_search') != '') {
            $rec_search_list = @unserialize(C('rec_search'));
			$rec_value = array();
			foreach($rec_search_list as $v){
				$rec_value[] = $v['value'];
			}
			
        }
        output_data(array('hot_info'=>$result ? $rec_value : array()));
    }

    /**
     * 高级搜索
     */
    public function search_advOp() {
        $area_list = Model('area')->getAreaList(array('area_deep'=>1),'area_id,area_name');
        if (C('contract_allow') == 1) {
            $contract_list = Model('contract')->getContractItemByCache();
            $_tmp = array();$i = 0;
            foreach ($contract_list as $k => $v) {
                $_tmp[$i]['id'] = $v['cti_id'];
                $_tmp[$i]['name'] = $v['cti_name'];
                $i++;
            }
        }
        output_data(array('area_list'=>$area_list ? $area_list : array(),'contract_list'=>$_tmp));
    }

    /**
     * android客户端版本号
     */
    public function apk_versionOp() {
        $version = C('mobile_apk_version');
        $url = C('mobile_apk');
        if(empty($version)) {
           $version = '';
        }
        if(empty($url)) {
            $url = '';
        }

        output_data(array('version' => $version, 'url' => $url));
    }

    public function testOp(){
        $model_class = Model('goods_class');
        $goods_class = $model_class->get_all_category(1);
        output_data($goods_class);
    }
}
