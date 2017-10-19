<?php
/**
 * 我的地址
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

class member_addressControl extends mobileMemberControl {

    public function __construct() {
        parent::__construct();
    }


    public function  set_default_addressOp(){
        if(!$_POST['address_id']){
            output_error('收货地址编号不能为空');
        }
        $address_id = intval($_POST['address_id']);
        $member_id=$this->member_info['member_id'];
        $model_address = Model('address');
        if( !$model_address->unsetDefaultAddress($member_id) ){
            output_error('操作失败');
        }
        $ret=$model_address->setDefaultAddress($address_id,$member_id);
        if(!$ret){
            output_error('操作失败');
        }else{
            output_data('1');
        }
    }
    /**
     * 地址列表
     */
    public function address_listOp() {
        $model_address = Model('address');
        $address_list = $model_address->getAddressList(array('member_id'=>$this->member_info['member_id']));
        output_data(array('address_list' => $address_list,'address_count' => count($address_list)));
    }

    /**
     * 地址详细信息
     */
    public function address_infoOp() {
        $address_id = intval($_POST['address_id']);

        $model_address = Model('address');

        $condition = array();
        $condition['address_id'] = $address_id;
        $address_info = $model_address->getAddressInfo($condition);
        if(!empty($address_id) && $address_info['member_id'] == $this->member_info['member_id']) {
            output_data(array('address_info' => $address_info));
        } else {
            output_error('地址不存在');
        }
    }

    /**
     * 删除地址
     */
    public function address_delOp() {
        $address_id = intval($_POST['address_id']);

        $model_address = Model('address');

        $condition = array();
        $condition['address_id'] = $address_id;
        $condition['member_id'] = $this->member_info['member_id'];
        $model_address->delAddress($condition);
        output_data('1');
    }

    /**
     * 新增地址
     */
    public function address_addOp() {
        $model_address = Model('address');

        $address_info = $this->_address_valid();
        $member_id = $this->member_info['member_id'];
        if($address_info['is_default'] == NULL){
            $address_count = $model_address->getAddressCount(array('member_id' => $member_id));
           $address_count > 0 ? $address_info['is_default']='0' : $address_info['is_default']='1';
        }
        if($address_info['is_default'] == '1'){
            $ret=$model_address->unsetDefaultAddress($member_id);
            if(!$ret){output_error('设置为非默认地址失败');}
        }

        $result = $model_address->addAddress($address_info);
        if($result) {
            output_data(array('address_id' => $result));
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 新增地址
     */
    public function member_areaOp() {
        $model_address = Model('address');
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'省不能为空'),   //省
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'市区不能为空'),   //市
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'区域不能为空'),   //区域
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $address_info = array();
        $address_info['member_id'] = $this->member_info['member_id'];
        $address_info['pr_id'] = intval($_POST['pr_id']);
        $address_info['city_id'] = intval($_POST['city_id']);
        $address_info['area_id'] = intval($_POST['area_id']);
        $address_info['default_area'] = 1;

        $result = $model_address->addAddress($address_info);
        if($result) {
            output_data('1');
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 新增/编辑地址
     */
    public function address_addeditOp() {
        $address_id = intval($_POST['address_id']);
        $model_address = Model('address');
        $address_info = $this->_address_valid();
        $member_id = $this->member_info['member_id'];
        if($address_info['is_default'] == NULL){
            $address_count = $model_address->getAddressCount(array('member_id' => $member_id));
            $address_count > 0 ? $address_info['is_default']='0' : $address_info['is_default']='1';
        }
        if($address_info['is_default'] == '1'){
            $ret=$model_address->unsetDefaultAddress($member_id);
            if(!$ret){output_error('设置为非默认地址失败');}
        }

        if($address_id > 0){//编辑
            //验证地址是否为本人
            $old_address_info = $model_address->getOneAddress($address_id);
            if ($old_address_info['member_id'] != $this->member_info['member_id']) {
                output_error('参数错误');
            }
            $result = $model_address->editAddress($address_info, array('address_id' => $address_id));
            if($result) {
                output_data(array('address_id' => $address_id));
            } else {
                output_error('保存失败');
            }
        }else{//新增
            $result = $model_address->addAddress($address_info);
            if($result) {
                output_data(array('address_id' => $result));
            } else {
                output_error('保存失败');
            }
        }

        
    }

    /**
     * 编辑地址
     */
    public function address_editOp() {
        $address_id = intval($_POST['address_id']);
        $model_address = Model('address');
        $address_info = $this->_address_valid();
        $member_id = $this->member_info['member_id'];
        if($address_info['is_default'] == NULL){
            $address_count = $model_address->getAddressCount(array('member_id' => $member_id));
            $address_count > 0 ? $address_info['is_default']='0' : $address_info['is_default']='1';
        }
        if($address_info['is_default'] == '1'){
            $ret=$model_address->unsetDefaultAddress($member_id);
            if(!$ret){output_error('设置为非默认地址失败');}
        }

        //验证地址是否为本人
        $address_info = $model_address->getOneAddress($address_id);
        if ($address_info['member_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        $address_info = $this->_address_valid();
        $result = $model_address->editAddress($address_info, array('address_id' => $address_id));
        if($result) {
            output_data(array('address_id'=>$address_id));
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 验证地址数据
     */
    private function _address_valid() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["true_name"],"require"=>"true","message"=>'姓名不能为空'),
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'省不能为空'),   //省
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'市区不能为空'),   //市
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'区域不能为空'),   //区域
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'地区不能为空'),   //江苏 苏州市 太仓市
            array("input"=>$_POST["address"],"require"=>"true","message"=>'地址不能为空'),     //弇山西路20号
            array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>'联系方式不能为空')   //手机号和电话号码任意选一个
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $data = array();
        $data['member_id'] = $this->member_info['member_id'];
        $data['true_name'] = $_POST['true_name'];
        $data['pr_id'] = intval($_POST['pr_id']);
        $data['city_id'] = intval($_POST['city_id']);
        $data['area_id'] = intval($_POST['area_id']);
        $data['area_info'] = $_POST['area_info'];
        $data['address'] = $_POST['address'];
        $data['tel_phone'] = $_POST['tel_phone'];
        $data['mob_phone'] = $_POST['mob_phone'];
        $data['is_default'] = $_POST['is_default'];
        $data['idcard_no'] = $_POST['idcard_no'];
        $data['idcard_front'] = $_POST['fpic'];
        $data['idcard_back'] = $_POST['bpic'];
        return $data;
    }

    /**
     * 地区列表
     */
    public function area_listOp() {
        $area_id = intval($_POST['area_id']);

        $model_area = Model('area');

        $condition = array();
        if($area_id > 0) {
            $condition['area_parent_id'] = $area_id;
        } else {
            $condition['area_deep'] = 1;
        }
        $area_list = $model_area->getAreaList($condition, 'area_id,area_name');
        output_data(array('area_list' => $area_list));
    }

    public function idcard_picOp() {
        if($_FILES['fpic']['error']=== 0){
            $pic='fpic';
        }elseif($_FILES['bpic']['error']=== 0){
            $pic='bpic';
        }else{
            output_error('上传失败，请尝试更换图片格式或小图片');
        }

        import('function.thumb');
        $member_id = $this->member_info['member_id'];
        //上传图片
        $upload = new UploadFile();
        //$upload->set('thumb_width', 340);
        //$upload->set('thumb_height',200);
        $ext = strtolower(pathinfo($_FILES[$pic]['name'], PATHINFO_EXTENSION));
        $random = substr(md5(time()),0,8);
        $filename=$member_id.'-'.$pic.$random.'.'.$ext;
        $upload->set('file_name',$filename);
        //$thumb_ext = substr(md5(time()),0,8);
        //$upload->set('thumb_ext',$thumb_ext);
        //$upload->set('ifremove',true);
        $upload->set('default_dir',"shop/idcards");

        if (!empty($_FILES[$pic]['tmp_name'])){
            $result = $upload->upfile($pic);
            if (!$result){
                output_error($upload->error);
            }
            //$pic_name=$member_id.'-'.$pic.$thumb_ext.'.'.$ext;
            $pic_url = "http://img2.htths.com/shop/idcards/" . $filename;
            output_data(array($pic=>$filename,'pic_url'=>$pic_url));
        }else{
            output_error('上传失败，请尝试更换图片格式或小图片');
        }

    }


}
