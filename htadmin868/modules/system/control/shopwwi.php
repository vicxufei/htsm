<?php
/**
 * @gyf
 */
defined('ByYfShop') or exit('非法进入,IP记录...');
class shopwwiControl extends SystemControl{
	 private $links = array(
	    array('url'=>'act=shopwwi&op=base','lang'=>'shopwwi_set'),
        array('url'=>'act=shopwwi&op=lc','lang'=>'lc_set'),
		array('url'=>'act=shopwwi&op=sms','lang'=>'sms_set'),
        
    );
	public function __construct(){
		parent::__construct();
		Language::read('shopwwi,setting');
	}
	    public function indexOp() {
        $this->baseOp();
    }
		 /**
     * 基本信息
     */
    public function baseOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            $update_array['shopwwi_stitle'] = $_POST['shopwwi_stitle'];
            $update_array['shopwwi_phone'] = $_POST['shopwwi_phone'];
            $update_array['shopwwi_time'] = $_POST['shopwwi_time'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,shopwwi_set'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,shopwwi_set'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();

        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'base'));
		//网 店 运 维shop wwi.com
		Tpl::setDirquna('system');
        Tpl::showpage('shopwwi.base');
    }
	
	 /**
     * 楼层快速直达列表
     */
    public function lcOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('shopwwi_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!$lc_list && !is_array($lc_list)) {
            $lc_list = array();
        }
        Tpl::output('lc_list',$lc_list);
        Tpl::output('top_link',$this->sublink($this->links,'lc'));
		Tpl::setDirquna('system');/*网 店 运 维shop wwi.com*/
        Tpl::showpage('shopwwi.lc');
    }

    /**
     * 楼层快速直达添加
     */
    public function lc_addOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('shopwwi_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!$lc_list && !is_array($lc_list)) {
            $lc_list = array();
        }
        if (chksubmit()) {
            if (count($lc_list) >= 8) {
                showMessage('最多可设置8个楼层','index.php?act=shopwwi&op=lc');
            }
            if ($_POST['lc_name'] != '' && $_POST['lc_value'] != '') {
                $data = array('name'=>stripslashes($_POST['lc_name']),'value'=>stripslashes($_POST['lc_value']));
                array_unshift($lc_list, $data);
            }
            $result = $model_setting->updateSetting(array('shopwwi_lc'=>serialize($lc_list)));
            if ($result){
                showMessage('保存成功','index.php?act=shopwwi&op=lc');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('system');/*网 店 运 维shop wwi.com*/

        Tpl::showpage('shopwwi.lc_add');
    }

    /**
     * 删除
     */
    public function lc_delOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('shopwwi_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!empty($lc_list) && is_array($lc_list) && intval($_GET['id']) >= 0) {
            unset($lc_list[intval($_GET['id'])]);
        }
        if (!is_array($lc_list)) {
            $lc_list = array();
        }
        $result = $model_setting->updateSetting(array('shopwwi_lc'=>serialize(array_values($lc_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 编辑
     */
    public function lc_editOp() {
        $model_setting = Model('setting');
        $lc_info = $model_setting->getRowSetting('shopwwi_lc');
        if ($lc_info !== false) {
            $lc_list = @unserialize($lc_info['value']);
        }
        if (!is_array($lc_list)) {
            $lc_list = array();
        }
        if (!chksubmit()) {
            if (!empty($lc_list) && is_array($lc_list) && intval($_GET['id']) >= 0) {
                $current_info = $lc_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('system');/*网 店 运 维shop wwi.com*/
            Tpl::showpage('shopwwi.lc_add');
        } else {
            if ($_POST['lc_name'] != '' && $_POST['lc_value'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $lc_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['lc_name']),'value'=>stripslashes($_POST['lc_value']));
            }
            $result = $model_setting->updateSetting(array('shopwwi_lc'=>serialize($lc_list)));
            if ($result){
                showMessage('编辑成功','index.php?act=shopwwi&op=lc');
            }
            showMessage('编辑失败');
        }


    }
		/**
	 * 短信平台设置 
	 */
	public function smsOp(){
		$model_setting = Model('setting');
		if (chksubmit()){
			$update_array = array();
			$update_array['shopwwi_sms_type'] 	= $_POST['shopwwi_sms_type'];
			$update_array['shopwwi_sms_tgs'] 	= $_POST['shopwwi_sms_tgs'];
			$update_array['shopwwi_sms_zh'] 	= $_POST['shopwwi_sms_zh'];
			$update_array['shopwwi_sms_pw'] 	= $_POST['shopwwi_sms_pw'];
			$update_array['shopwwi_sms_key'] 	= $_POST['shopwwi_sms_key'];
			$update_array['shopwwi_sms_signature'] 		= $_POST['shopwwi_sms_signature'];
			$update_array['shopwwi_sms_bz'] 	= $_POST['shopwwi_sms_bz'];
			$result = $model_setting->updateSetting($update_array);
			if ($result === true){
				$this->log(L('nc_edit,sms_set'),1);
				showMessage(L('nc_common_save_succ'));
			}else {
				$this->log(L('nc_edit,sms_set'),0);
				showMessage(L('nc_common_save_fail'));
			}
		}
		$list_setting = $model_setting->getListSetting();
		Tpl::output('list_setting',$list_setting);
		
        Tpl::output('top_link',$this->sublink($this->links,'sms'));
		Tpl::setDirquna('system');/*网 店 运 维shop wwi.com*/
        Tpl::showpage('shopwwi.sms');
	}
}