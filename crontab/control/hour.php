<?php
/**
 * 任务计划 - 小时执行的任务
 *
 *
 *
 *
 * @网店运维提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopwwi.com
 * @link       交流群号：111731672
 */
defined('ByShopWWI') or exit('Access Invalid!');

class hourControl extends BaseCronControl {
    /**
     * 执行频率常量 1小时
     * @var int
     */
    const EXE_TIMES = 3600;

    private $_doc;
    private $_xs;
    private $_index;
    private $_search;

    /**
     * 默认方法
     */
    public function indexOp() {
        //echo '12345';exit;
        //未付款订单超期自动关闭
        $this->_order_timeout_cancel();

        //更新全文搜索内容
        $this->_xs_update();
    }

    /**
     * 未付款订单超期自动关闭
     */
    private function _order_timeout_cancel() {

        //实物订单超期未支付系统自动关闭
        $_break = false;
        $model_order = Model('order');
        $logic_order = Logic('order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['chain_code'] = 0;
//        $condition['api_pay_time'] = 0;
        $condition['add_time'] = array('lt',TIMESTAMP - ORDER_AUTO_CANCEL_TIME * self::EXE_TIMES);
        //分批，每批处理100个订单，最多处理5W个订单
        for ($i = 0; $i < 500; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_order->getOrderList($condition, '', '*', '', 100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {

                $result = $logic_order->changeOrderStateCancel($order_info,'system','系统','超期未支付系统自动关闭订单',true,array('order_state'=>ORDER_STATE_NEW));

                if (!$result['state']) {
                    $this->log('实物订单超期未支付关闭失败SN:'.$order_info['order_sn']); $_break = true; break;
                }
            }
        }

        //虚拟订单超期未支付系统自动关闭
        $_break = false;
        $model_vr_order = Model('vr_order');
        $logic_vr_order = Logic('vr_order');
        $condition = array();
        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['api_pay_time'] = 0;
        $condition['add_time'] = array('lt',TIMESTAMP - ORDER_AUTO_CANCEL_TIME * self::EXE_TIMES);

        //分批，每批处理100个订单，最多处理5W个订单
        for ($i = 0; $i < 500; $i++){
            if ($_break) {
                break;
            }
            $order_list = $model_vr_order->getOrderList($condition, '', '*', '',100);
            if (empty($order_list)) break;
            foreach ($order_list as $order_info) {
                $result = $logic_vr_order->changeOrderStateCancel($order_info,'system','超期未支付系统自动关闭订单',false);
            }
            if (!$result['state']) {
                $this->log('虚拟订单超期未支付关闭失败SN:'.$order_info['order_sn']); $_break = true; break;
            }
        }
    }

    /**
     * 初始化对象
     */
    private function _ini_xs(){
        require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
        $this->_doc = new XSDocument();
        $this->_xs = new XS(C('fullindexer.appname'));
        $this->_index = $this->_xs->index;
        $this->_search = $this->_xs->search;
        $this->_search->setCharset(CHARSET);

    }


    /**
     * 全量创建索引
     */
    public function xs_createOp() {
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        $model = Model();
        try {
            //每次批量更新商品数
            $step_num = 100;
            $model_goods = Model('goods');
            //$_field = "CONCAT(goods_commonid,',',color_id)";
            //$_distinct = 'nc_distinct';
            $count = $model_goods->getGoodsOnlineCount(array());
            echo 'Total:'.$count."\n";
            if ($count != 0) {
                for ($i = 0; $i <= $count; $i = $i + $step_num){
                  //$goods_list = $model_goods->getGoodsOnlineList(array(), '*,'.$_field.' nc_distinct', 0, 'goods_id desc', "{$i},{$step_num}", $_distinct);
                    $goods_list = $model_goods->getGoodsOnlineList(array(), '*', 0, 'goods_id desc', "{$i},{$step_num}");
                    //只索引上架的商品,array("goods_state"=>1)
                    //sql查询出的商品不全 $goods_list = $model_goods->getGoodsList_yf(array("goods_state"=>1), '*,'.$_field.' nc_distinct', 0, 'goods_id desc', "{$i},{$step_num}", $_distinct);
                    $this->_build_goods($goods_list);
                    echo $i." ok\n";
                    flush();
                    ob_flush();
                }
            }

            if ($count > 0) {
                sleep(2);
                $this->_index->flushIndex();
                sleep(2);
                $this->_index->flushLogging();
            }
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }



    /**
     * 更新增量索引
     */
    public function _xs_update() {
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        $model = Model();
        try {
            //更新多长时间内的新增(编辑)商品信息，该时间一般与定时任务触发间隔时间一致,单位是秒,默认3600
            $step_time = self::EXE_TIMES + 60;
            //每次批量更新商品数
            $step_num = 100;

            $model_goods = Model('goods');
            $condition = array();
            $condition['goods_edittime'] = array('egt',TIMESTAMP-$step_time);
            $_field = "CONCAT(goods_commonid,',',color_id)";
            $_distinct = 'nc_distinct';
            $count = $model_goods->getGoodsOnlineCount($condition,"distinct ".$_field);
            echo 'Total:'.$count."\n";
            for ($i = 0; $i <= $count; $i = $i + $step_num){
                $goods_list = $model_goods->getGoodsOnlineList($condition, '*,'.$_field.' nc_distinct', 0, '', "{$i},{$step_num}", $_distinct);
                //通过commonid得到所有goods_id，然后删除全文索引中的goods_id内容
                $goods_commonid_array = array();
                foreach ($goods_list as $_v) {
                    $goods_commonid_array[] = $_v['goods_commonid'];
                }
                if ($goods_commonid_array) {
                    $condition1 = array('goods_commonid' => array('in',$goods_commonid_array));
                    $goods_list1 = $model_goods->getGoodsOnlineList($condition1, 'goods_id', 0, '', '', false);
                    if ($goods_list1) {
                        $goods_id_array = array();
                        foreach ($goods_list1 as $_v) {
                            $goods_id_array[] = $_v['goods_id'];
                        }
                        $this->_index->del($goods_id_array);
                    }
                }
                $this->_build_goods($goods_list);
                echo $i." ok\n";
                flush();
                ob_flush();
            }
            if ($count > 0) {
                sleep(2);
                $this->_index->flushIndex();
                sleep(2);
                $this->_index->flushLogging();
            }
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     * 索引商品数据
     * @param array $goods_list
     */
    private function _build_goods($goods_list = array()) {
        if (empty($goods_list) || !is_array($goods_list)) return;
        $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
        $model_goods = Model('goods');
        $goods_commonid_array = array();
        $goods_id_array = array();
        $store_id_array = array();
        foreach ($goods_list as $k => $v) {
            $goods_commonid_array[] = $v['goods_commonid'];
            $goods_id_array[] = $v['goods_id'];
            $store_id_array[] = $v['store_id'];
        }

        //商品图
        $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => array('in',$goods_commonid_array)), '*', 'is_default desc,goods_image_id asc');

        // 店铺
        $store_list = Model('store')->getStoreMemberIDList($store_id_array);

        $kill_common_ids = array();
        //首先进行一次循环，根据商品分类的show_type设置，确定哪些SKU显示，缓存哪些商品图
        foreach ($goods_list as $k => $goods_info) {
            if ($goods_class[$goods_info['gc_id']]['show_type'] == 1) {
                //原来的显示方式，显示多个SKU,每个SKU显示各自的图
                foreach ($image_list as $image_info) {
                    if ($goods_info['goods_commonid'] == $image_info['goods_commonid']
                    && $goods_info['store_id'] == $image_info['store_id']
                    && $goods_info['color_id'] == $image_info['color_id']) {
                        $goods_list[$k]['image'][] = $image_info['goods_image'];
                    }
                }
            } else {
                //一个commonid中只显示一个SKU，显示各个SKU的主图
                foreach ($image_list as $image_info) {
                    if ($goods_info['goods_commonid'] == $image_info['goods_commonid']
                    && $goods_info['store_id'] == $image_info['store_id']
                    && $image_info['is_default'] == 1) {
                        $goods_list[$k]['image'][] = $image_info['goods_image'];
                    }
                }
                if (in_array($goods_info['goods_commonid'],$kill_common_ids)) {
                    unset($goods_list[$k]);
                } else {
                    $kill_common_ids[] = $goods_info['goods_commonid'];
                }
            }
        }

        //取common表内容
        $condition_common = array();
        $condition_common['goods_commonid'] = array('in',$goods_commonid_array);
        $goods_common_list = $model_goods->getGoodsCommonOnlineList($condition_common,'*',0);
        $goods_common_new_list = array();
        foreach($goods_common_list as $k => $v) {
            $goods_common_new_list[$v['goods_commonid']] = $v;
        }

        //取属性表值
        $model_type = Model('type');
        $attr_list = $model_type->getGoodsAttrIndexList(array('goods_id'=>array('in',$goods_id_array)),0,'goods_id,attr_value_id');
        if (is_array($attr_list) && !empty($attr_list)) {
            $attr_value_list = array();
            foreach ($attr_list as $val) {
                $attr_value_list[$val['goods_id']][] = $val['attr_value_id'];
            }
        }

        //处理商品消费者保障服务信息


        //整理需要索引的数据
        foreach ($goods_list as $k => $v) {
			$cate_3 = $cate_2 = $cate_1 = null;
            $gc_id = $v['gc_id'];
            $depth = $goods_class[$gc_id]['depth'];
            if ($depth == 3) {
                $cate_3 = $gc_id; $gc_id = $goods_class[$gc_id]['gc_parent_id']; $depth--;
            }
            if ($depth == 2) {
                $cate_2 = $gc_id; $gc_id = $goods_class[$gc_id]['gc_parent_id']; $depth--;
            }
            if ($depth == 1) {
                $cate_1 = $gc_id; $gc_id = $goods_class[$gc_id]['gc_parent_id'];
            }
            $index_data = array();
            $index_data['pk'] = $v['goods_id'];
            $index_data['goods_id'] = $v['goods_id'];
            $index_data['goods_name'] = $v['goods_name'];
            $index_data['goods_jingle'] = $v['goods_jingle'];
            $index_data['brand_id'] = $v['brand_id'];
            $index_data['brand_country_code'] = $v['brand_country_code'];
            $index_data['transport_id'] = $v['transport_id'];
            $index_data['goods_promotion_price'] = $v['goods_promotion_price'];
            $index_data['goods_click'] = $v['goods_click'];
            $index_data['goods_salenum'] = $v['goods_salenum'];
            $index_data['goods_barcode'] = $v['goods_barcode'];
            // 判断店铺是否为自营店铺
            $index_data['gc_id'] = $v['gc_id'];
            $index_data['gc_name'] = str_replace('&gt;','',$goods_common_new_list[$v['goods_commonid']]['gc_name']);
            $index_data['brand_name'] = $goods_common_new_list[$v['goods_commonid']]['brand_name'];
            if (!empty($attr_value_list[$v['goods_id']])) {
                $index_data['attr_id'] = implode('_',$attr_value_list[$v['goods_id']]);
            }
            if (!empty($cate_1)) {
                $index_data['cate_1'] = $cate_1;
            }else{
				$index_data['cate_1'] = 0;
			}
            if (!empty($cate_2)) {
                $index_data['cate_2'] = $cate_2;
            }else{
				$index_data['cate_2'] = 0;
			}
            if (!empty($cate_3)) {
                $index_data['cate_3'] = $cate_3;
            }else{
				$index_data['cate_3'] = 0;
			}


            $index_data['main_body'] = serialize(array(
            	   'goods_promotion_type' => $v['goods_promotion_type'],
                   'goods_marketprice' => $v['goods_marketprice'],
             //      'is_virtual' => $v['is_virtual'],
                   'evaluation_count' => $v['evaluation_count'],
                   'goods_storage' => $v['goods_storage'],
                   'goods_image' => $v['goods_image'],
                   'goods_edittime' => $v['goods_edittime'],
                   'goods_attr' => $v['goods_attr'],
                   'brand_country_en' => $v['brand_country_en'],
                   'brand_country_zh' => $v['brand_country_zh']
            ));
            //添加到索引库
             $this->_doc->setFields($index_data);
             $this->_index->update($this->_doc);
        }
    }

    public function xs_clearOp(){

        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        try {
            $this->_index->clean();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    public function xs_flushLoggingOp(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();
        try {
            $this->_index->flushLogging();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }

    public function xs_flushIndexOp(){
        if (!C('fullindexer.open')) return;
        $this->_ini_xs();

        try {
            $this->_index->flushIndex();
        } catch (XSException $e) {
            $this->log($e->getMessage());
        }
    }
}
