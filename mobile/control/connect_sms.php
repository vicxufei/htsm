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

class connect_smsControl extends mobileHomeControl{
    public function __construct(){
        parent::__construct();
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
}
