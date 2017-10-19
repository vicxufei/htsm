<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<?php
function yf_log($file,$txt)
{
	$fp =  fopen($file,'ab+');
	fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
	fwrite($fp,$txt);
	fwrite($fp,"\r\n\r\n\r\n");
	fclose($fp);
}
yf_log('receive.txt',var_export($_REQUEST,true));
echo 'haha';
exit();



	function param_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
	
	/* version,merchantAcctId,terminalId,dealId,customCode,customVersion,merCustomCode,merCustomName,ecpShortName,ecpDomainName,competCustom,iaqInstCode,payerIdType,payerName,payerIdNumber,orderId,orderCurrency,orderAmt,freightAmt,goodsAmt,taxAmt,offsetAmt,declareId,decResult,errorCode,errorMsg */
	//
	$kq_check_all_para=param_ck_null($_REQUEST['competCustom'],'competCustom');
	$kq_check_all_para.=param_ck_null($_REQUEST['customCode'],'customCode');
	$kq_check_all_para.=param_ck_null($_REQUEST['customVersion'],'customVersion');
	//支付流水号
	$kq_check_all_para.=param_ck_null($_REQUEST['dealId'],'dealId');
	//申报状态 10-接收成功、11-接收失败、21-申报失败、20-申报成功
	$kq_check_all_para.=param_ck_null($_REQUEST['decResult'],'decResult');
	//申报ID
	$kq_check_all_para.=param_ck_null($_REQUEST['declareId'],'declareId');
	$kq_check_all_para.=param_ck_null($_REQUEST['ecpDomainName'],'ecpDomainName');
	$kq_check_all_para.=param_ck_null($_REQUEST['ecpShortName'],'ecpShortName');
	//失败时返回的错误代码，可以为空。
	if(!empty($_REQUEST['errorCode'])){
		$kq_check_all_para.=param_ck_null($_REQUEST['errorCode'],'errorCode');
	}
	if(!empty($_REQUEST['errorMsg'])){
		$kq_check_all_para.=param_ck_null($_REQUEST['errorMsg'],'errorMsg');
	}
	//失败时返回的错误信息，可以为空。
	$kq_check_all_para.=param_ck_null($_REQUEST['freightAmt'],'freightAmt');
	$kq_check_all_para.=param_ck_null($_REQUEST['goodsAmt'],'goodsAmt');
	$kq_check_all_para.=param_ck_null($_REQUEST['iaqInstCode'],'iaqInstCode');
	$kq_check_all_para.=param_ck_null($_REQUEST['merCustomCode'],'merCustomCode');
	$kq_check_all_para.=param_ck_null($_REQUEST['merCustomName'],'merCustomName');
	//商户号 由我司提供
	$kq_check_all_para.=param_ck_null($_REQUEST['merchantAcctId'],'merchantAcctId');
	$kq_check_all_para.=param_ck_null($_REQUEST['offsetAmt'],'offsetAmt');
	$kq_check_all_para.=param_ck_null($_REQUEST['orderAmt'],'orderAmt');
	$kq_check_all_para.=param_ck_null($_REQUEST['orderCurrency'],'orderCurrency');
	$kq_check_all_para.=param_ck_null($_REQUEST['orderId'],'orderId');
	$kq_check_all_para.=param_ck_null($_REQUEST['payerIdNumber'],'payerIdNumber');
	$kq_check_all_para.=param_ck_null($_REQUEST['payerIdType'],'payerIdType');
	$kq_check_all_para.=param_ck_null($_REQUEST['payerName'],'payerName');
	$kq_check_all_para.=param_ck_null($_REQUEST['taxAmt'],'taxAmt');
	//终端号 由我司提供
	$kq_check_all_para.=param_ck_null($_REQUEST['terminalId'],'terminalId');
	//版本号 固定值：1.0
	$kq_check_all_para.=param_ck_null($_REQUEST['version'],'version');

	$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);

	$MAC=base64_decode(urldecode($_REQUEST['signMsg']));

	$fp = fopen("./ChinaPnR.rsa.pem", "r"); 
	$cert = fread($fp, filesize("./ChinaPnR.rsa.pem")); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body, $MAC, $pubkeyid, OPENSSL_ALGO_SHA1); 

	$verify_result = "验签失败";
	$decl_result = "";
	if ($ok == 1) { 
		$verify_result = "验签成功";
		//响应
		echo "<result>1</result>";
		switch($_REQUEST['decResult']){
				//具体状态码映射以接口文档为准
				case '20':
						//此处做商户逻辑处理
						
						$decl_result="申报成功";
						break;
				default:
						$decl_result="申报失败";
						break;
		}

	}else{
		echo "验签失败";
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>海关接受demo信息页面</title>
	</head>
	<body>
		<div align="center">
			<h3 align="center">结果信息</h3>
			<font color="#ff0000">（该页面实际可以没有，只用来显示相关结果）</font>
			<table width="500" border="1" style="border-collapse: collapse" bordercolor="green" align="center">
				<tr>
					<td width="20%">验签结果:</td>
					<td width="30%">
						<?PHP echo $ok; ?>
						<?PHP echo $verify_result; ?>
					</td>
					<td width="20%">申报结果:</td>
					<td width="30%">
						<?PHP echo $decl_result; ?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
		