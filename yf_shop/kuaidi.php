<?php
//$curlUrl ='http://www.kuaidi100.com/query?type=ems&postid=1064618669622&id=1&valicode=&temp=fOKs&sessionid=&tmp=wiif';
  $curlUrl ='http://www.kuaidi100.com/query?type=ems&postid=1064618669622&id=1&valicode=&temp=0.690901523603568';
//$curlUrl ='http://api.htths.com/index.php?act=index&op=slide';
//$matches = parse_url($url);
//var_export($matches);
//exit();
//$scheme = $matches['scheme'];
//$host = $matches['host'];

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $curlUrl);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//$data = curl_exec($ch);
//var_export($data);
//curl_close($ch);
//echo $data;

//var_export($data);

function pget($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_REFERER, "http://www.kuaidi100.com/");
    curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
//    curl_setopt($curl, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
//    curl_setopt($curl, CURLOPT_COOKIEFILE,$GLOBALS ['cookie_file']); // 读取上面所储存的Cookie信息
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    var_export($tmpInfo);
    if (curl_errno($curl)) {
        echo '操作超时!';
    }
    curl_close($curl); // 关键CURL会话
    return $tmpInfo; // 返回数据
}

pget($curlUrl);


