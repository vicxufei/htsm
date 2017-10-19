<?php
require __DIR__ . '/vendor/autoload.php';

use \Curl\Curl;

// curl --request GET "https://httpbin.org/get?key=value"

$curl = new Curl();
//$curl->setUserAgent('MyUserAgent/0.0.1 (+https://www.example.com/bot.html)');
$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
$curl->get('http://49.84.207.182:48000/BokeDee/loginDesign.jsp');

if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo 'Response:' . "\n";
    var_dump($curl->response);
}
