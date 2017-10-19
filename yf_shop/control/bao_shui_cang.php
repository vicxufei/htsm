<?php
/**
 * 保税仓
 * 9.4.15 返回订单信息
 * @gyf
 */

defined('ByYfShop') or exit('非法进入,IP记录...');

function yf_log($file, $txt)
{
  $fp = fopen($file, 'ab+');
  fwrite($fp, '-----------' . date('Y-m-d H:i:s', time()) . '-----------------');
  fwrite($fp, $txt);
  fwrite($fp, "\r\n\r\n\r\n");
  fclose($fp);
}

class bao_shui_cangControl extends BaseHomeControl
{
  // 9.4.15  返回订单信息
  public function order_stateOp()
  {
    yf_log('bao_shui_cang-order_state.txt', var_export($_POST, true));

    $APP_SECRET = "HThjN3Et";
    $version = $_POST['version'];
    $bustype = $_POST['bustype'];
    $sign = $_POST['sign'];
    $base64_data = $_POST['data'];
//    $version = 'v1.0';
//    $bustype = 'OrderStatus';
//    $base64_data = 'eyJzdGF0dXNMaXN0IjpbeyJvcmRlckNvZGUiOiJURVMwMDAwMDAwMDAwMDAwMDAwMDAwOSIsIm91dE9yZGVyQ29kZSI6IlRFUzAwMDAwMDAwMDAwMDAwMDAwMDA5IiwiY2FycmllciI6bnVsbCwibG9naXN0aWNzTk8iOm51bGwsInJlYWxXZWlnaHQiOm51bGwsIm9yZGVyU3RhdHVzIjoiMTAwIiwib3JkZXJDb21tZW50Ijoi5Yib5bu66K6i5Y2V5oiQ5YqfLiIsIm9yZGVyTWFrZURhdGUiOiIyMDE3LTA0LTI1IDE3OjUyOjU4In1dfQ';

    $signCheck = md5($version . $APP_SECRET . $bustype . $base64_data);
//    echo strtoupper($signCheck);
//    die;
    if (strtoupper($signCheck) == $sign) {
      $data = base64_decode($base64_data);
      $decodeData = json_decode($data, true);
      $statusList = $decodeData['statusList']['0'];
      $output = [
        "ROWSET" => [
          [
            "orderCode" => $statusList['orderCode'],
            "resultCode" => "1000",
            "resultMsg" => "和团商贸_太划算商城接受订单清关状态成功!",
          ],
        ],
      ];
      echo json_encode($output,JSON_UNESCAPED_UNICODE);
    } else {
      $output = [
        "ROWSET" => [
          [
            "orderCode" => 'null',
            "resultCode" => "1001",
            "resultMsg" => "和团商贸_太划算商城接受订单清关状态失败!",
          ],
        ],
      ];
      echo json_encode($output,JSON_UNESCAPED_UNICODE);
    }


  }
}
