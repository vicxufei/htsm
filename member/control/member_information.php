<?php
/**
 * 用户中心
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');

class member_informationControl extends BaseMemberControl {
    /**
     * 用户中心
     *
     * @param
     * @return
     */
    public function indexOp() {
        //$this->memberOp();
        redirect('http://i.htths.com/index.php?controller=member_security&action=index');
    }
    /**
     * 我的资料【用户中心】
     *
     * @param
     * @return
     */
    public function memberOp() {

        Language::read('member_home_member');
        $lang   = Language::getLangContent();

        $model_member   = Model('member');

        if (chksubmit()){

            $member_array   = array();
            $member_array['member_truename']    = $_POST['member_truename'];
            $member_array['member_sex']         = $_POST['member_sex'];
            $member_array['member_qq']          = $_POST['member_qq'];
            $member_array['member_ww']          = $_POST['member_ww'];
            $member_array['member_areaid']      = $_POST['area_id'];
            $member_array['member_cityid']      = $_POST['city_id'];
            $member_array['member_provinceid']  = $_POST['province_id'];
            $member_array['member_areainfo']    = $_POST['area_info'];
            if (strlen($_POST['birthday']) == 10){
                $member_array['member_birthday']    = $_POST['birthday'];
            }
            $member_array['member_privacy']     = serialize($_POST['privacy']);
            $update = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$member_array);

            $message = $update? $lang['nc_common_save_succ'] : $lang['nc_common_save_fail'];
            showDialog($message,'reload',$update ? 'succ' : 'error');
        }

        if($this->member_info['member_privacy'] != ''){
            $this->member_info['member_privacy'] = unserialize($this->member_info['member_privacy']);
        } else {
            $this->member_info['member_privacy'] = array();
        }
        Tpl::output('member_info',$this->member_info);

        self::profile_menu('member','member');
        Tpl::output('menu_sign','profile');
        Tpl::output('menu_sign_url','index.php?controller=member_information&action=member');
        Tpl::output('menu_sign1','baseinfo');
        Tpl::display2('member');
    }

public function uploadOp() {
		if (!chksubmit()){
			redirect('index.php?act=member_information&op=avatar');
		}
		import('function.thumb');
		Language::read('member_home_member,cut');
		$lang	= Language::getLangContent();
		$member_id = $_SESSION['member_id'];

        //上传图片
        $upload = new UploadFile();
        $upload->set('thumb_width', 500);
        $upload->set('thumb_height',499);
        $ext = strtolower(pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION));
        $upload->set('file_name',"avatar_$member_id.$ext");
        $upload->set('thumb_ext','_new');
        $upload->set('ifremove',true);
        $upload->set('default_dir',ATTACH_AVATAR);
        if (!empty($_FILES['pic']['tmp_name'])){
            $result = $upload->upfile('pic');
            if (!$result){
                showMessage($upload->error,'','html','error');
            }
        }else{
            showMessage('上传失败，请尝试更换图片格式或小图片','','html','error');
        }
        self::profile_menu('member','avatar');
        Tpl::output('menu_sign','profile');
        Tpl::output('menu_sign_url','index.php?act=member_information&op=member');
        Tpl::output('menu_sign1','avatar');
        Tpl::output('newfile',$upload->thumb_image);
	Tpl::output('height',get_height(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
	Tpl::output('width',get_width(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));


        Tpl::showpage('member_profile.avatar');
    }

    /**
     * 裁剪
     *
     */
    public function cutOp(){
        if (chksubmit()){
            $thumb_width = 120;
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $x2 = $_POST["x2"];
            $y2 = $_POST["y2"];
            $w = $_POST["w"];
            $h = $_POST["h"];
            $scale = $thumb_width/$w;
            $_POST['newfile'] = str_replace('..', '', $_POST['newfile']);
            if (strpos($_POST['newfile'],"avatar_{$_SESSION['member_id']}_new.") !== 0) {
                redirect('index.php?act=member_information&op=avatar');
            }
            $src = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$_POST['newfile'];
            $avatarfile = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS."avatar_{$_SESSION['member_id']}.jpg";

			import('function.thumb');
			$cropped = resize_thumb($avatarfile, $src,$w,$h,$x1,$y1,$scale);
            @unlink($src);
            Model('member')->editMember(array('member_id'=>$_SESSION['member_id']),array('member_avatar'=>'avatar_'.$_SESSION['member_id'].'.jpg'));
            $_SESSION['avatar'] = 'avatar_'.$_SESSION['member_id'].'.jpg';
            redirect('index.php?act=member_information&op=avatar');
        }
    }

    /**
     * 更换头像
     *
     * @param
     * @return
     */
    public function avatarOp() {
        Language::read('member_home_member,cut');
        $member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'],'member_avatar');
        Tpl::output('member_avatar',$member_info['member_avatar']);
        self::profile_menu('member','avatar');
        Tpl::output('menu_sign','profile');
        Tpl::output('menu_sign_url','index.php?act=member_information&op=member');
        Tpl::output('menu_sign1','avatar');
        Tpl::display('member_profile.avatar');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array     = array();
        switch ($menu_type) {
            case 'member':
                $menu_array = array(
                1=>array('menu_key'=>'member',  'menu_name'=>Language::get('home_member_base_infomation'),'menu_url'=>'index.php?act=member_information&op=member'),
                5=>array('menu_key'=>'avatar',  'menu_name'=>Language::get('home_member_modify_avatar'),'menu_url'=>'index.php?act=member_information&op=avatar'));
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
