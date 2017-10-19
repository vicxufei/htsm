<?php
/**
 * 商品分类
 *
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */



defined('ByShopWWI') or exit('Access Invalid!');
class goods_classControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexOp() {
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $this->_get_class_list($_GET['gc_id']);
        } else {
            $this->_get_root_class();
        }
    }
    public function index2Op() {
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $this->_get_class_list($_GET['gc_id']);
        } else {
            $this->_get_root_class2();
        }
    }
	

	public function get_child_allOp() {
        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $this->_get_class_list($_GET['gc_id']);
        }
    }
    /**
     * 返回一级分类列表
     */
    private function _get_root_class() {
        $model_goods_class = Model('goods_class');
        //数据库查询手机端分类图片
        //$model_mb_category = Model('mb_category');

        //读取所有分类(一级,二级,三级)
        //$goods_class_array = Model('goods_class')->getGoodsClassForCacheModel();

        $class_list = $model_goods_class->getGoodsClassListByParentId(0);
        //$mb_categroy = $model_mb_category->getLinkList(array());
        //$mb_categroy = array_under_reset($mb_categroy, 'gc_id');
        foreach ($class_list as $key => $value) {
            //删除多余的分类字段
            unset($class_list[$key]['type_id'],$class_list[$key]['type_name'],$class_list[$key]['gc_parent_id'],$class_list[$key]['gc_virtual'],$class_list[$key]['gc_title'],$class_list[$key]['gc_keywords'],$class_list[$key]['gc_description'],$class_list[$key]['show_type'],$class_list[$key]['commis_rate']);
            //if(!empty($mb_categroy[$value['gc_id']])) {
            //    $class_list[$key]['image'] = $mb_categroy[$value['gc_id']]['gc_thumb'];
            //} else {
            //    $class_list[$key]['image'] = '';
            //}
        }

        output_data(array('class_list' => $class_list));
    }

    /**
     * 返回一级分类列表
     */
    private function _get_root_class2() {
        $model_goods_class = Model('goods_class');

        $class_list = $model_goods_class->getGoodsClassListByParentId(0);
        $new_class_list = array();
        foreach ($class_list as $key => $value) {
            //删除多余的分类字段
            //unset($class_list[$key]['type_id'],$class_list[$key]['type_name'],$class_list[$key]['gc_parent_id'],$class_list[$key]['gc_virtual'],$class_list[$key]['gc_title'],$class_list[$key]['gc_keywords'],$class_list[$key]['gc_description'],$class_list[$key]['show_type'],$class_list[$key]['commis_rate']);
            $new_class_list[$value['gc_sort']]['gc_id'] = $value['gc_id'];
            $new_class_list[$value['gc_sort']]['gc_name'] = $value['gc_name'];
        }

        output_data(array('class_list' => $new_class_list));
    }

    /**
     * 根据分类编号返回下级分类列表
     */
    private function _get_class_list($gc_id,$type='data') {
        $goods_class_array = Model('goods_class')->getGoodsClassForCacheModel();

        $goods_class = $goods_class_array[$gc_id];

        if(empty($goods_class['child'])) {
            //无下级分类返回0
			if($type=='data'){
				output_data(array('class_list' => '0'));
			}
        } else {
            //返回下级分类列表
            $class_list = array();
            $child_class_string = $goods_class_array[$gc_id]['child'];
            $child_class_array = explode(',', $child_class_string);
            foreach ($child_class_array as $child_class) {
                $class_item = array();
                $class_item['gc_id'] .= $goods_class_array[$child_class]['gc_id'];
                $class_item['gc_name'] .= $goods_class_array[$child_class]['gc_name'];
                
				//if($type=='array'){
					$class_item['child'] = $this->_get_class_list($child_class,'array');
				//}
				//$class_item['child'] = '--------'.$type;
				$class_list[] = $class_item;
            }
			if($type=='data'){
				output_data(array('class_list' => $class_list));
			}else{
				return $class_list;
			}
        }
    }
}
