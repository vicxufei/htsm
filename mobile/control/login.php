<?php
/**
 * 前台登录 退出操作
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

class loginControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录
     */
    public function indexOp(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }

        $model_member = Model('member');

        $array = array();
        $array['member_name']   = $_POST['username'];
        $array['member_passwd'] = md5($_POST['password']);
        $member_info = $model_member->getMemberInfo($array);
        if(empty($member_info) && preg_match('/^0?(13|15|17|18|14)[0-9]{9}$/i', $_POST['username'])) {//根据会员名没找到时查手机号
            $array = array();
            $array['member_mobile']   = $_POST['username'];
            $array['member_passwd'] = md5($_POST['password']);
            $member_info = $model_member->getMemberInfo($array);
        }
        if(empty($member_info) && (strpos($_POST['username'], '@') > 0)) {//按邮箱和密码查询会员
            $array = array();
            $array['member_email']   = $_POST['username'];
            $array['member_passwd'] = md5($_POST['password']);
            $member_info = $model_member->getMemberInfo($array);
        }

        if(!empty($member_info)) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client'],$_POST['version'],$_POST['deviceid']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'member_avatar'=> getMemberAvatarForID($member_info['member_avatar']),'userid' => $member_info['member_id'], 'member_mobile' => $member_info['member_mobile'], 'key' => $token));
            } else {
                output_error('登录失败');
            }
        } else {
            output_error('用户名密码错误');
        }
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client, $app_version, $device_id) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        $condition = array();
        $condition['member_id'] = $member_id;
        $condition['client_type'] = $client;
        $model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;
        $mb_user_token_info['app_version'] = $app_version;
        $mb_user_token_info['device_id'] = $device_id;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }

    /**
     * 注册
     */
    public function registerOp(){
        $model_member   = Model('member');

        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client'], $_POST['version'],$_POST['deviceid']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
            output_error($member_info['error']);
        }

    }
    /**
     * 注册
     */
    public function sms_registerOp(){
        $model_member   = Model('member');

        $register_info = array();
        $register_info['username'] = empty($_POST['username']) ? $_POST['phone'] : $_POST['username'];
        $register_info['phone'] = $_POST['phone'];
        $register_info['password'] = $_POST['password'];

        $captcha = $_POST['sms_captcha'];
        $condition = array();
        $condition['log_phone'] = $register_info['phone'];
        $condition['log_captcha'] = $captcha;
        $condition['log_type'] = 1;
        $model_sms_log = Model('sms_log');
        $sms_log = $model_sms_log->getSmsInfo($condition);
        if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-1800)) {//半小时内进行验证为有效
            output_error('动态码错误或已过期，重新输入');
        }

        $member_info = $model_member->sms_register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client'], $_POST['version'],$_POST['deviceid']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
            output_error($member_info['error']);
        }

    }

    /**
     * 找回密码
     */
    public function find_passwordOp(){
        $phone = $_POST['phone'];
        $captcha = $_POST['sms_captcha'];

        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["phone"],      "require"=>"true",      "message"=>'请正确输入手机号'),
            array("input"=>$_POST["sms_captcha"],      "require"=>"true",      "message"=>'请正确输入手机验证码'),
            array("input"=>$_POST["password"],      "require"=>"true",      "message"=>'请正确输入密码'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $condition = array();
        $condition['log_phone'] = $phone;
        $condition['log_captcha'] = $captcha;
        $condition['log_type'] = 3;
        $model_sms_log = Model('sms_log');
        $sms_log = $model_sms_log->getSmsInfo($condition);
        if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-1800)) {//半小时内进行验证为有效
            output_error('动态码错误或已过期，重新输入');
        }
        $model_member = Model('member');
        $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));
        if(!empty($member)) {
            $new_password = md5($_POST['password']);
            $model_member->editMember(array('member_id'=> $member['member_id']),array('member_passwd'=> $new_password));
            output_data('1');
        }
    }

}
