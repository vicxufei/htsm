<?php
function pget($method,$id){
//    $url = 'http://192.168.18.109:48001/class/'.$method.'?id='.$id;
    $url = 'http://api.htths.com';

    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址1
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转1
    curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容1
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回1



    $tmpInfo = curl_exec($curl); // 执行操作
    var_export($tmpInfo);
    if (curl_errno($curl)) {
        echo '操作超时!';
    }
    curl_close($curl); // 关键CURL会话
    return $tmpInfo; // 返回数据
}


function curl_get($method,$id){
    $url = 'http://49.84.207.182:48001/class/'.$method.'?id='.$id;
    $url = 'http://www.htths.com';
    echo $url;

    $headers = array('Content-type: text/plain', 'Content-length: 100');

    try {
        $ch = curl_init();

        if (FALSE === $ch)
            throw new Exception('failed to initialize');

        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt ($ch, CURLOPT_PORT , 8089);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $content = curl_exec($ch);

        if (FALSE === $content)


            throw new Exception(curl_error($ch), curl_errno($ch));

        echo $content;
        print_r($content);
        // ...process $content now
    } catch(Exception $e) {
        trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
    }
}

pget('add',2100);
//curl_get('add',2100);


