<?php
//function yf_log($file,$txt)
//{
//  $fp =  fopen($file,'ab+');
//  fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
//  fwrite($fp,$txt);
//  fwrite($fp,"\r\n\r\n\r\n");
//  fclose($fp);
//}
//yf_log('receiveBg.txt',var_export($_REQUEST,true));

$_REQUEST = array (
	'dealTime' => '20170611100614',
	'errCode' => '000000',
	'merchantAcctId' => '1000526000101',
	'orderTime' => '20170611100355',
	'orderCurrency' => 'USD',
	'dealId' => '2000003075',
	'version' => '3.0',
	'bankId' => 'ccb',
	'terminalId' => '10001',
	'payResult' => '10',
	'ext1' => 'ext1',
	'orderAmount' => '1040',
	'ext2' => 'ext2',
	'signMsg' => 'MzySVB%2FNFqyAt0zx1P83IKd3O%2BXPriJZGbvGokP7n9eOgyxTbNS5Vc30c1FpPlWJyghnc6FDDLVOUAPLfwurgRi2IUKW0HbJRiQGpVdbMdGXVSJ5bOXGmh8Ji5AUxsPUK%2ByWP51%2FYIGm7I6NN5oGiJetMiwq1hcV4%2BgiNWLF4mo%3D',
	'payType' => '30',
	'orderId' => '20170611100355',
);

function param_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}

//银行交易号 ，ChinaPnr交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
$kq_check_all_para=param_ck_null($_REQUEST[bankDealId],'bankDealId');
//银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
//$kq_check_all_para.=param_ck_null($_REQUEST[bankId],'bankId');
// ChinaPnr交易号，商户每一笔交易都会在ChinaPnr生成一个交易号。
$kq_check_all_para.=param_ck_null($_REQUEST[dealId],'dealId');
//ChinaPnr交易时间，ChinaPnr对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
$kq_check_all_para.=param_ck_null($_REQUEST[dealTime],'dealTime');
//错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
$kq_check_all_para.=param_ck_null($_REQUEST[errCode],'errCode');
//扩展字段1，该值与提交时相同
$kq_check_all_para.=param_ck_null($_REQUEST[ext1],'ext1');
//扩展字段2，该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[ext2],'ext2');
//人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[orderAmount],'orderAmount');
$kq_check_all_para.=param_ck_null($_REQUEST[orderCurrency],'orderCurrency');
//商户订单号，,该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[orderId],'orderId');
//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[orderTime],'orderTime');
//处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
$kq_check_all_para.=param_ck_null($_REQUEST[payResult],'payResult');
//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[payType],'payType');
//商户终端号，该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[terminalId],'terminalId');
//网关版本，该值与提交时相同。
$kq_check_all_para.=param_ck_null($_REQUEST[version],'version');

$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);

$signMsgDe=	urldecode($_REQUEST[signMsg]);
$MAC=base64_decode($signMsgDe);
$trans_body_de=urldecode($trans_body);

$fp = fopen("./ChinaPnR.rsa.pem", "r");
$cert = fread($fp, filesize("./ChinaPnR.rsa.pem"));
fclose($fp);
$pubkeyid = openssl_get_publickey($cert);

$ok = openssl_verify($trans_body_de, $MAC, $pubkeyid);

if ($ok == 1) {
	switch($_REQUEST[payResult]){
		case '10':
			//此处做商户逻辑处理
			$rtnOK=1;
			$msg="deal Success, check sign success";
			break;
		default:
			$rtnOK=0;
			$msg="deal failed, check sign success";
			break;

	}

}else{
	$rtnOK=0;
	$msg="check sign failed";
}

?>

<result><?PHP echo $msg; ?></result>

