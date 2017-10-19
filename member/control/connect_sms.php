<?php
/**
 * 手机短信登录
 *
 *
 *
 * @网店运维提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopwwi.com
 * @link       交流群号：111731672
 */



defined('ByShopWWI') or exit('Access Invalid!');

class connect_smsControl extends BaseLoginControl{
    public function __construct(){
        parent::__construct();
        Language::read("home_login_register,home_login_index");
        Tpl::output('hidden_nctoolbar', 1);
        $model_member = Model('member');
        $model_member->checkloginMember();
    }
    /**
     * 手机注册验证码
     */
    public function indexOp(){
        $this->registerOp();
    }
    /**
     * 手机注册
     */
    public function registerOp(){
        $model_member = Model('member');
        $phone = $_POST['phone'];
        $captcha = $_POST['register_captcha'];
        if (chksubmit_yf() && strlen($phone) == 11 && strlen($captcha) == 6){
            //$member_name = $_POST['member_name'];
            $member_name = $phone;
            $member = $model_member->getMemberInfo(array('member_name'=> $member_name));//检查重名
            if(!empty($member)) {
                output_error('用户名已被注册');
                //showDialog('用户名已被注册','','error');
            }
            $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));//检查手机号是否已被注册
            if(!empty($member)) {
                output_error('手机号已被注册');
                //showDialog('手机号已被注册','','error');
            }
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['log_captcha'] = $captcha;
            $condition['log_type'] = 1;
            $model_sms_log = Model('sms_log');
            $sms_log = $model_sms_log->getSmsInfo($condition);
            if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-14400)) {//2小时内进行验证为有效
                output_error('动态码错误或已过期，重新输入');
                //showDialog('动态码错误或已过期，重新输入','','error');
            }
            
            $member = array();
            $member['member_name'] = $member_name;
            $member['member_passwd'] = $_POST['password'];
            //$member['member_email'] = $_POST['email'];
            $member['member_mobile'] = $phone;
            $member['member_mobile_bind'] = 1;
            $result = $model_member->addMember($member);
            if($result) {
                $member = $model_member->getMemberInfo(array('member_name'=> $member_name));
                $model_member->createSession($member,true);//自动登录
                output_data('注册成功');
                //showDialog('注册成功',urlMember('member_information', 'member'),'succ');
            } else {
                output_error(Language::get('nc_common_save_fail'));
                //showDialog(Language::get('nc_common_save_fail'),'','error');
            }
        } else {
            output_error('请填写完整');
            //$phone = $_GET['phone'];
            //$num = substr($phone,-4);
            //$member_name = 'u'.$num;
            //$member = $model_member->getMemberInfo(array('member_name'=> $member_name));
            //if(!empty($member)) {
            //    for ($i = 1;$i < 999;$i++) {
            //        $num += 1;
            //        $member_name = 'u'.$num;
            //        $member = $model_member->getMemberInfo(array('member_name'=> $member_name));
            //        if(empty($member)) {//查询为空表示当前会员名可用
            //            break;
            //        }
            //    }
            //}
            //Tpl::output('member_name',$member_name);
            //Tpl::output('password',rand(100000, 999999));
            //Tpl::showpage('connect_sms.register','null_layout');
        }
    }

    /**
     * 短信动态码
     */
    public function get_captchaOp(){
        $state = '发送失败';
        $phone = $_GET['phone']|$_POST['phone'];
        if (strlen($phone) == 11){
            $log_type = $_GET['type']|$_POST['type'];//短信类型:1为注册,2为登录,3为找回密码
            $model_sms_log = Model('sms_log');
            $condition = array();
            $condition['log_ip'] = getIp();
            $condition['log_type'] = $log_type;
            $sms_log = $model_sms_log->getSmsInfo($condition);
            if(!empty($sms_log) && ($sms_log['add_time'] > TIMESTAMP-600)) {//同一IP10分钟内只能发一条短信
                $state = '同一IP地址十分钟内，请勿多次获取动态码！';
                output_error($state);
            } else {
                $state = 'true';
                $log_array = array();
                $model_member = Model('member');
                $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));
                $captcha = rand(100000, 999999);
                $log_msg = '您于'.date("Y-m-d");
                switch ($log_type) {
                    case '1':
                        if(C('sms_register') != 1) {
                            $state = '系统没有开启手机注册功能';
                            output_error($state);
                        }
                        if(!empty($member)) {//检查手机号是否已被注册
                            $state = '当前手机号已被注册，请更换其他号码。';
                            output_error($state);
                        }
                        //$log_msg .= '申请注册会员，动态码：'.$captcha.'。';
                        $log_msg .= '提交手机注册验证，验证码是：'.$captcha;
                        break;
                    case '2':
                        if(C('sms_login') != 1) {
                            $state = '系统没有开启手机登录功能';
                            output_error($state);
                        }
                        if(empty($member)) {//检查手机号是否已绑定会员
                            $state = '当前手机号未注册，请检查号码是否正确。';
                            output_error($state);
                        }
                        $log_msg .= '申请登录，动态码：'.$captcha.'。';
                        $log_array['member_id'] = $member['member_id'];
                        $log_array['member_name'] = $member['member_name'];
                        break;
                    case '3':
                        if(empty($member)) {//检查手机号是否已绑定会员
                            $state = '当前手机号未注册，请检查号码是否正确。';
                            output_error($state);
                        }
                        $log_msg .= '申请重置登录密码，动态码：'.$captcha.'。';
                        $log_array['member_id'] = $member['member_id'];
                        $log_array['member_name'] = $member['member_name'];
                        break;
                    default:
                        $state = '参数错误';
                        output_error($state);
                        break;
                }
                if($state == 'true'){
                    $sms = new Sms();
                    $result = $sms->send($phone,$log_msg);
                    if($result){
                        $log_array['log_phone'] = $phone;
                        $log_array['log_captcha'] = $captcha;
                        $log_array['log_ip'] = getIp();
                        $log_array['log_msg'] = $log_msg;
                        $log_array['log_type'] = $log_type;
                        $log_array['add_time'] = time();
                        $model_sms_log->addSms($log_array);
                        $state = '短信已发出!';
                    } else {
                        $state = '手机短信发送失败';
                        output_error($state);
                    }
                }
            }
        } else {
            $state = '验证码错误';
            output_error($state);
        }
        output_data($state);
        exit($state);
    }

    /**
     * 短信动态码
     */
    public function get_captcha2Op(){
        $state = '发送失败';
        $phone = $_GET['phone']|$_POST['phone'];
        if (strlen($phone) == 11){
            $log_type = $_GET['type']|$_POST['type'];//短信类型:1为注册,2为登录,3为找回密码
            $model_sms_log = Model('sms_log');
            $condition = array();
            $condition['log_ip'] = getIp();
            $condition['log_type'] = $log_type;
            $sms_log = $model_sms_log->getSmsInfo($condition);
            if(!empty($sms_log) && ($sms_log['add_time'] > TIMESTAMP-3600)) {//同一IP1小时内只能发一条短信
                $state = '同一IP地址十分钟内，请勿多次获取动态码！';
            } else {
                $state = 'true';
                $log_array = array();
                $model_member = Model('member');
                $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));
                $captcha = rand(100000, 999999);
                $log_msg = '您于'.date("Y-m-d");
                switch ($log_type) {
                    case '1':
                        if(C('sms_register') != 1) {
                            $state = '系统没有开启手机注册功能';
                        }
                        if(!empty($member)) {//检查手机号是否已被注册
                            $state = '当前手机号已被注册，请更换其他号码。';
                        }
                        //$log_msg .= '申请注册会员，动态码：'.$captcha.'。';
                        $log_msg .= '提交手机注册验证，验证码是：'.$captcha;
                        break;
                    case '2':
                        if(C('sms_login') != 1) {
                            $state = '系统没有开启手机登录功能';
                        }
                        if(empty($member)) {//检查手机号是否已绑定会员
                            $state = '当前手机号未注册，请检查号码是否正确。';
                        }
                        $log_msg .= '申请登录，动态码：'.$captcha.'。';
                        $log_array['member_id'] = $member['member_id'];
                        $log_array['member_name'] = $member['member_name'];
                        break;
                    case '3':
                        if(C('sms_password') != 1) {
                            $state = '系统没有开启手机找回密码功能';
                        }
                        if(empty($member)) {//检查手机号是否已绑定会员
                            $state = '当前手机号未注册，请检查号码是否正确。';
                        }
                        $log_msg .= '申请重置登录密码，动态码：'.$captcha.'。';
                        $log_array['member_id'] = $member['member_id'];
                        $log_array['member_name'] = $member['member_name'];
                        break;
                    default:
                        $state = '参数错误';
                        break;
                }
                if($state == 'true'){
                    $sms = new Sms();
                    $result = $sms->send($phone,$log_msg);
                    if($result){
                        $log_array['log_phone'] = $phone;
                        $log_array['log_captcha'] = $captcha;
                        $log_array['log_ip'] = getIp();
                        $log_array['log_msg'] = $log_msg;
                        $log_array['log_type'] = $log_type;
                        $log_array['add_time'] = time();
                        $model_sms_log->addSms($log_array);
                    } else {
                        $state = '手机短信发送失败';
                    }
                }
            }
        } else {
            $state = '验证码错误';
        }
        exit($state);
    }
    /**
     * 验证注册动态码
     */
    public function check_captchaOp(){
        $state = false;
        $message = '';
        $phone = $_GET['phone']|$_POST['phone'];
        $captcha = $_GET['sms_captcha']|$_POST['sms_captcha'];
        if (strlen($phone) == 11 && strlen($captcha) == 6){
            $state = true;
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['log_captcha'] = $captcha;
            $condition['log_type'] = 1;
            $model_sms_log = Model('sms_log');
            $sms_log = $model_sms_log->getSmsInfo($condition);
            if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-14400)) {//半小时内进行验证为有效
                $state = false;
                $message = '动态码错误或已过期，重新输入';
            }
        }
        if($state){
            exit('true');
        }else{
            output_error($message);
        }
    }
    /**
     * 登录
     */
    public function loginOp(){
        if (checkSeccode($_POST['nchash'],$_POST['captcha'])){
            if(C('sms_login') != 1) {
                showDialog('系统没有开启手机登录功能','','error');
            }
            $phone = $_POST['phone'];
            $captcha = $_POST['sms_captcha'];
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['log_captcha'] = $captcha;
            $condition['log_type'] = 2;
            $model_sms_log = Model('sms_log');
            $sms_log = $model_sms_log->getSmsInfo($condition);
            if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-1800)) {//半小时内进行验证为有效
                showDialog('动态码错误或已过期，重新输入','','error');
            }
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));//检查手机号是否已被注册
            if(!empty($member)) {
                $model_member->createSession($member);//自动登录
                $reload = $_POST['ref_url'];
                if(empty($reload)) {
                    $reload = urlMember('member', 'home');
                }
                showDialog('登录成功',$reload,'succ');
            }
        }
    }
    /**
     * 找回密码
     */
    public function find_passwordOp(){
        if (checkSeccode($_POST['nchash'],$_POST['captcha'])){
            $phone = $_POST['phone'];
            $captcha = $_POST['sms_captcha'];
            $condition = array();
            $condition['log_phone'] = $phone;
            $condition['log_captcha'] = $captcha;
            $condition['log_type'] = 3;
            $model_sms_log = Model('sms_log');
            $sms_log = $model_sms_log->getSmsInfo($condition);
            if(empty($sms_log) || ($sms_log['add_time'] < TIMESTAMP-1800)) {//半小时内进行验证为有效
                showDialog('动态码错误或已过期，重新输入','','error');
            }
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));//检查手机号是否已被注册
            if(!empty($member)) {
                $new_password = md5($_POST['password']);
                $model_member->editMember(array('member_id'=> $member['member_id']),array('member_passwd'=> $new_password));
                $model_member->createSession($member);//自动登录
                showDialog('密码修改成功',urlMember('member_information', 'member'),'succ');
            }
        }
    }

}
