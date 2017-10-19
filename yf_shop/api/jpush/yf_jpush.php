<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/8/27
 * Time: 下午3:38
 */
//require __DIR__ . '/autoload.php';

use JPush\Client as JPush;

//$registration_id = getenv('registration_id');


// 简单推送示例
function appPush($alias,$alert, $order_id){
    $app_key = '58b17b7832612ee0445af0fd';
    $master_secret = 'f3301abfb220afa0348b67f4';
    $client = new JPush($app_key, $master_secret);

    $push_payload = $client->push()
        ->setPlatform('all')
        //->addAllAudience()
        ->addAlias($alias)
        ->setNotificationAlert($alert)
        ->iosNotification($alert, array(
            'sound' => '',
            'badge' => 1,
            'content-available' => true,
            'extras' => array(
                'orderid' => $order_id,
            ),
        ))
        ->androidNotification($alert, array(
            'build_id' => 1,
            'extras' => array(
                'orderid' => $order_id,
            ),
        ));
    try {
        $response = $push_payload->send();
        //print_r($response);
        return true;
    }catch (\JPush\Exceptions\APIConnectionException $e) {
        // try something here
        //print $e;
        return false;
    } catch (\JPush\Exceptions\APIRequestException $e) {
        // try something here
        //print $e;
        return false;
    }

}

//test
//appPush('18606220656','您的订单已发货!');


// 完整的推送示例
//try {
//    $response = $client->push()
//        ->setPlatform(array('ios', 'android'))
//        ->addAlias('18606220656')
//        //->addTag(array('tag1', 'tag2'))
//        //->addRegistrationId($registration_id)
//        ->setNotificationAlert('Hi, JPush')
//        ->iosNotification('Hello IOS', array(
//            'sound' => 'YFhello jpush',
//            'badge' => 2,
//            'content-available' => true,
//            'category' => 'jiguang',
//            'extras' => array(
//                'key' => 'value',
//                'jiguang'
//            ),
//        ))
//        ->androidNotification('Hello Android', array(
//            'title' => 'YF2hello jpush',
//            //'build_id' => 2,
//            'extras' => array(
//                'key' => 'value',
//                'jiguang'
//            ),
//        ))
//        //->message('message content', array(
//        //    'title' => 'TTThello jpush',
//        //    'content_type' => 'text',
//        //    'extras' => array(
//        //        'key' => 'value',
//        //        'jiguang'
//        //    ),
//        //))
//        ->options(array(
//            'sendno' => 100,
//            'time_to_live' => 100,
//            'apns_production' => false,
//            'big_push_duration' => 100
//        ))
//        ->send();
//} catch (\JPush\Exceptions\APIConnectionException $e) {
//    // try something here
//    print $e;
//} catch (\JPush\Exceptions\APIRequestException $e) {
//    // try something here
//    print $e;
//}
//
//print_r($response);

