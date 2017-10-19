<?php
/**
 * 我的商城
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

class member_indexControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexOp() {
        $member_info = array();
		$member_info = $this->getMemberAndGradeInfo(true,'member_id,member_name,member_cityid,member_areaid,member_areainfo,member_avatar,member_mobile');
        if($this->member_info['member_avatar']){
            $member_info['avator'] = getMemberAvatarForID($this->member_info['member_avatar']).'?'.time();
        }else{
            $member_info['avator'] = 'http://img2.htths.com/user.jpg';
        }
		$favorites_model = Model('favorites');
		$member_info['favorites_goods'] = $favorites_model->getGoodsFavoritesCountByGoodsId('',$this->member_info['member_id']);//商品收藏数
        $model_order = Model('order');
        //交易提醒 - 显示数量
        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'NewCount');
        $member_info['order_payed_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'PayCount');
        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'SendCount');
        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'EvalCount');
        output_data(array('member_info' => $member_info));
    }
    public function index206Op() {
        $member_info = array();
        $member_info = $this->getMemberAndGradeInfo(true,'member_id,member_name,member_cityid,member_areaid,member_areainfo,member_avatar,member_mobile');
        if($this->member_info['member_avatar']){
            $member_info['avator'] = getMemberAvatarForID($this->member_info['member_avatar']).'?'.time();
        }else{
            $member_info['avator'] = '';
        }
        $favorites_model = Model('favorites');
        $member_info['favorites_goods'] = $favorites_model->getGoodsFavoritesCountByGoodsId('',$this->member_info['member_id']);//商品收藏数
        $model_order = Model('order');
        //交易提醒 - 显示数量
        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'NewCount');
        $member_info['order_payed_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'PayCount');
        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'SendCount');
        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'EvalCount');
        output_data(array('member_info' => $member_info));
    }
	
	/**
     * 我的积分
     */
    public function my_assetOp() {
		$member_info = $this->getMemberAndGradeInfo(true);
		$point = $this->member_info['member_points'];
		$predepoit = $this->member_info['available_predeposit'];
		$balance = $this->member_info['available_rc_balance'];
		$voucher =  Model('voucher')->getCurrentAvailableVoucherCount($this->member_info['member_id']); //取得当前有效代金券数量
		$redpacket =  Model('redpacket')->getCurrentAvailableRedpacketCount($this->member_info['member_id']); //取得当前有效红包数量

		if($_GET["fields"]=='predepoit'){
			output_data(array('predepoit' => $predepoit));
		}elseif($_GET["fields"]=='available_rc_balance'){
			output_data(array('available_rc_balance' => $balance));
		}else{
			output_data(array('point' => $point,'predepoit'=>$predepoit,'available_rc_balance'=>$balance,'redpacket'=>$redpacket,'voucher'=>$voucher));
		}
	}
	protected function getMemberAndGradeInfo($is_return = false, $field = '*'){
        $member_info = array();
        //会员详情及会员级别处理
        if($this->member_info['member_id']) {
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfoByID($this->member_info['member_id'],$field);
            if ($member_info){
                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
                $member_info = array_merge($member_info,$member_gradeinfo);
                $member_info['security_level'] = $model_member->getMemberSecurityLevel($member_info);
            }
        }
        if ($is_return == true){//返回会员信息
            return $member_info;
        } else {//输出会员信息
            Tpl::output('member_info',$member_info);
        }
    }

    public function avataruploadOp() {
        //if (!chksubmit()){
        //    redirect('index.php?act=member_information&op=avatar');
        //}
        import('function.thumb');

        $member_id = $this->member_info['member_id'];

        //上传图片
        $upload = new UploadFile();
        //$upload->set('thumb_width', 500);
        //$upload->set('thumb_height',499);
        $ext = strtolower(pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION));
        $file_name = 'avatar_'.$member_id.'.'.$ext;
        $upload->set('file_name',$file_name);
        //$upload->set('thumb_ext','_new');
        //$upload->set('ifremove',true);
        $upload->set('default_dir',ATTACH_AVATAR);
        if (!empty($_FILES['pic']['tmp_name'])){
            $result = $upload->upfile('pic');
            if (!$result){
                output_error($upload->error);
            }
        }else{
            output_error('上传失败，请尝试更换图片格式或小图片');
        }

        //Tpl::output('newfile',$upload->thumb_image);
        //Tpl::output('height',get_height(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
        //Tpl::output('width',get_width(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/'.$upload->thumb_image));
        //
        //
        //Tpl::showpage('member_profile.avatar');
        //$avatar_pic='avatar_'.$member_id.'_new.'.$ext;
        $rt=Model('member')->editMember(array('member_id'=>$member_id),array('member_avatar'=>$file_name));
        if($rt){
            $pic_url='http://img2.htths.com/'.ATTACH_AVATAR.'/'.$file_name.'?'.time();
            output_data(array('pic_url'=>$pic_url));
        }else{
            output_error('操作失败请重试!');
        }

    }


}
