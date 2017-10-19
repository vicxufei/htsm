<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<?PHP
	function str2arr1 ($str){
		$arr = explode("&",$str);
		$r = array();
		foreach ($arr as $val ){
			$t = explode("=",$val);
			$r[$t[0]]= $t[1];
		}
		return $r;
	}
	//util函数//结束


	/* version,bgUrl,signType,merchantAcctId,terminalId,dealId,customCode,
    	customVersion,merCustomCode,merCustomName,payerIdType,payerName,
    	payerIdNumber,orderId,orderCurrency,orderAmt,freightAmt,goodsAmt,
    	taxAmt,offsetAmt */
	/*ecpShortName,ecpDomainName,competCustom,iaqInstCode,bizType,ext1,ext2,signMsg*/
	//网关版本 固定值：1.0
	$version = "1.0"; 
	//后台通知地址 互联网可访问
//  $bgUrl = "http://localhost/receive.php";
	$bgUrl = "http://www.htths.com/php-custom/receive.php";
	//签名类型 固定值：4(RSA加签)
	$signType = "4";
	//商户号 由我司提供
	$merchantAcctId = "1000526000101";  // todo
	//终端号 由我司提供
	$terminalId = "hg0002";  // todo
	//支付流水号 条件必输；对于分期支付，允许多个，英文逗号分隔  (数字,数字)
	$dealId = "";  // todo
	//海关代码 海关编码,参考字典
	$customCode = "NBHG";  // todo
	//海关版本 可空
	$customVersion = "";
	//商户海关备案号 商户在海关的备案号
	$merCustomCode = "1111"; // todo
	//商户海关备案名称
	$merCustomName = "测试商户"; // todo

	//电商平台企业简写
	$ecpShortName = "1111"; // todo
	//电商平台互联网域名
	$ecpDomainName = "www.xxx.com";  // todo
	//主管海关代码
	$competCustom = "1111"; // todo
	//检验检疫机构代码
	$iaqInstCode = "1111"; // todo

	//证件类型 如果商户提交以商户为准,证件类型默认为身份证 1-身份证
	$payerIdType = "1";
	//姓名
	$payerName = "朱义伟"; // todo
	//证件号
	$payerIdNumber = "321281199302133913"; // todo
	//商户订单号 字符串,只允许使用字母、数字、_,并以_，字母或数字开头,每商户提交的订单号，必须在自身账户交易中唯一；,合并支付时，填写商户子订单号；分期支付时，填写合并订单号
	$orderId = date("YmdHis"); // todo
	//订币种 默认CNY
	$orderCurrency = "CNY";
	//订单金额 整型数字,以分为单位。比方10元，提交时金额应为1000,商户页面显示金额可以转换成以元为单位显示
	$orderAmt = "600"; // todo
	//物流费 没值传0，整型数字,以分为单位。比方10元，提交时金额应为1000,商户页面显示金额可以转换成以元为单位显示
	$freightAmt = "600"; // todo
	//货款 整型数字,以分为单位。比方10元，提交时金额应为1000,商户页面显示金额可以转换成以元为单位显示
	$goodsAmt = "0";  // todo
	//关税 没值传0，整型数字,以分为单位。比方10元，提交时金额应为1000,商户页面显示金额可以转换成以元为单位显示
	$taxAmt = "0";  // todo
	//抵扣金额 整型数字,以分为单位。比方10元，提交时金额应为1000,商户页面显示金额可以转换成以元为单位显示
	$offsetAmt = "0";  // todo

	//业务类型 预留字段
	$bizType = "";
	//扩展字段1 英文或中文字符串
	$ext1 = "";
	//扩展字段2 英文或中文字符串
	$ext2 = "";

	function param_ck_null($kq_va,$kq_na){
		if($kq_va == ""){
			$kq_va="";
		}else{
			return $kq_va=$kq_na.'='.$kq_va.'&';
		}
	}

$kq_all_para=param_ck_null($version,'version');
	$kq_all_para.=param_ck_null($bgUrl,"bgUrl");
	$kq_all_para.=param_ck_null($signType,'signType');
	$kq_all_para.=param_ck_null($merchantAcctId,'merchantAcctId');
	$kq_all_para.=param_ck_null($terminalId,'terminalId');
	$kq_all_para.=param_ck_null($dealId,'dealId');
	$kq_all_para.=param_ck_null($customCode,'customCode');
	$kq_all_para.=param_ck_null($customVersion,'customVersion');
	$kq_all_para.=param_ck_null($merCustomCode,'merCustomCode');
	$kq_all_para.=param_ck_null($merCustomName,'merCustomName');
	$kq_all_para.=param_ck_null($payerIdType,'payerIdType');
	$kq_all_para.=param_ck_null($payerName,'payerName');
	$kq_all_para.=param_ck_null($payerIdNumber,'payerIdNumber');
	$kq_all_para.=param_ck_null($orderId,'orderId');
	$kq_all_para.=param_ck_null($orderCurrency,'orderCurrency');
	$kq_all_para.=param_ck_null($orderAmt,'orderAmt');
	$kq_all_para.=param_ck_null($freightAmt,'freightAmt');
	$kq_all_para.=param_ck_null($goodsAmt,'goodsAmt');
	$kq_all_para.=param_ck_null($taxAmt,'taxAmt');
	$kq_all_para.=param_ck_null($offsetAmt,'offsetAmt');

	$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);

	/////////////  RSA 签名计算 ///////// 开始 //
	$fp = fopen("./10005260001.pem", "r");
	$priv_key = fread($fp, filesize('./10005260001.pem'));
	fclose($fp);
	$pkeyid = openssl_get_privatekey($priv_key);

	// compute signature
	openssl_sign($kq_all_para, $signMsg, $pkeyid,OPENSSL_ALGO_SHA1);

	// free the key from memory
	openssl_free_key($pkeyid);

	 $signMsg = base64_encode($signMsg);
	/////////////  RSA 签名计算 ///////// 结束 //


	/* version,bgUrl,signType,merchantAcctId,terminalId,dealId,customCode,
    	customVersion,merCustomCode,merCustomName,payerIdType,payerName,
    	payerIdNumber,orderId,orderCurrency,orderAmt,freightAmt,goodsAmt,
    	taxAmt,offsetAmt */
	/*ecpShortName,ecpDomainName,competCustom,iaqInstCode,bizType,ext1,ext2,signMsg*/
	/////////////  提交请求 ///////// 开始 //	
	$post_data = array (
		"version" => $version,
		"bgUrl" => $bgUrl,
		"signType" => $signType,
		"merchantAcctId" => $merchantAcctId,
		"terminalId" => $terminalId,
		"dealId" => $dealId,
		"customCode" => $customCode,
		"customVersion" => $customVersion,
		"merCustomCode" => $merCustomCode,
		"merCustomName" => $merCustomName,
		"ecpShortName" => $ecpShortName,
		"ecpDomainName" => $ecpDomainName,
		"competCustom" => $competCustom,
		"iaqInstCode" => $iaqInstCode,
		"payerIdType" => $payerIdType,
		"payerName" => $payerName,
		"payerIdNumber" => $payerIdNumber,
		"orderId" => $orderId,
		"orderCurrency" => $orderCurrency,
		"orderAmt" => $orderAmt,
		"freightAmt" => $freightAmt,
		"goodsAmt" => $goodsAmt,
		"taxAmt" => $taxAmt,
		"offsetAmt" => $offsetAmt,
		"bizType" => $bizType,
		"ext1" => $ext1,
		"ext2" => $ext2,
		"signMsg" => $signMsg
	);
	
	$url = 'https://mertest.chinapnr.com/custom/applyImptDec.do';
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, 1 );
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));// 设置 POST 参数
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	$respData = curl_exec($curl);
	curl_close($curl);

var_export($respData);
exit();

	/////////////  提交请求 ///////// 结束//
	/////////////  验签 ///////// 开始//
	$signature_params_arr = array("competCustom","customCode","customVersion","dealId","decResult","declareId","ecpDomainName","ecpShortName","errorCode","errorMsg","freightAmt","goodsAmt","iaqInstCode","merCustomCode","merCustomName","merchantAcctId","offsetAmt","orderAmt","orderCurrency","orderId","payerIdNumber","payerIdType","payerName","taxAmt","terminalId","version");

	$result_arr = str2arr1($respData);
	$kq_check_all_para = "";
	
	foreach($signature_params_arr as $param){
		$kq_check_all_para.=array_key_exists($param, $result_arr) ? param_ck_null($result_arr[$param], $param) : "";
	}
	
	$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);
	
	$signMsgResp=$result_arr['signMsg'];
	
	$signMsgDe=	urldecode($signMsgResp);
	$MAC=base64_decode($signMsgDe);
	$trans_body_de=urldecode($trans_body);
	
	$fp = fopen("./ChinaPnR.rsa.pem", "r"); 
	$cert = fread($fp, filesize("./ChinaPnR.rsa.pem")); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body_de, $MAC, $pubkeyid); 
	echo $ok;
	/////////////  验签 ///////// 结束//


?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>海关请求demo信息页面</title>
		<style type="text/css">
			td{text-align:center}
		</style>
	</head>
	<body>
		<div align="center">
			<h3 align="center">提交信息</h3>
			<font color="#ff0000">（该页面仅做参考）</font>
    		<table width="800" border="1" style="border-collapse: collapse" bordercolor="green" align="center">
				<tr>
					<td width="20%">版本号:</td>
					<td width="30%"><input value="<?PHP echo $version; ?>" name="version" id="version" /></td>
					<td width="20%">加密方式:</td>
					<td width="30%"><input value="<?PHP echo $signType; ?>" name="signType" id="signType" /></td>
				</tr>
				<tr>
					<td width="20%">后台通知地址:</td>
					<td width="30%" colspan="3">
					   <input style="width: 500px;" value="<?PHP echo $bgUrl; ?>" name="bgUrl" id="bgUrl">
					</td>
				</tr>
				<tr>
	                <td width="20%">商户号:</td>
	                <td width="30%"><input value="<?PHP echo $merchantAcctId; ?>" name="merchantAcctId" id="merchantAcctId" /></td>
	                <td width="20%">终端号:</td>
	                <td width="30%"><input value="<?PHP echo $terminalId; ?>" name="terminalId" id="terminalId" /></td>
	            </tr>
	            <tr>
	                <td width="20%">证件类型:</td>
	                <td width="30%"><input value="<?PHP echo $payerIdType; ?>" name="payerIdType" id="payerIdType" /></td>
	                <td width="20%">姓名:</td>
	                <td width="30%"><input value="<?PHP echo $payerName; ?>" name="payerName" id="payerName" /></td>
	            </tr>
	            <tr>
	                <td width="20%">身份证号:</td>
	                <td width="30%"><input value="<?PHP echo $payerIdNumber; ?>" name="payerIdNumber" id="payerIdNumber" /></td>
	            </tr>
				<tr>
	                <td width="20%">海关代码:</td>
	                <td width="30%"><input value="<?PHP echo $customCode; ?>" name="customCode" id="customCode" /></td>
	                <td width="20%">海关版本:</td>
	                <td width="30%"><input value="<?PHP echo $customVersion; ?>" name="customVersion" id="customVersion" /></td>
	            </tr>
	            <tr>
	                <td width="20%">商户海关备案号:</TD>
	                <td width="30%"><input value="<?PHP echo $merCustomCode; ?>" name="merCustomCode" id="merCustomCode" /></td>
	                <td width="20%">商户海关备案名称:</TD>
	                <td width="30%"><input value="<?PHP echo $merCustomName; ?>" name="merCustomName" id="merCustomName" /></td>
	            </tr>
	            <tr>
	                <td width="20%">商户备案号:</td>
	                <td width="30%"><input value="<?PHP echo $ecpShortName; ?>" name="ecpShortName" id="ecpShortName" /></td>
	                <td width="20%">电商平台互联网域名:</td>
	                <td width="30%"><input value="<?PHP echo $ecpDomainName; ?>" name="ecpDomainName" id="ecpDomainName" /></td>
	            </tr>
	            <tr>
	                <td width="20%">主管海关代码:</td>
	                <td width="30%"><input value="<?PHP echo $competCustom; ?>" name="competCustom" id="competCustom" /></td>
	                <td width="20%">检验检疫机构代码:</td>
	                <td width="30%"><input value="<?PHP echo $iaqInstCode; ?>" name="iaqInstCode" id="iaqInstCode" /></td>
	            </tr>
				<tr>
	                <td width="20%">订单号:</td>
	                <td width="30%"><input value="<?PHP echo $orderId; ?>" name="orderId" id="orderId" /></td>
	                <td width="20%">支付流水号:</td>
	                <td width="30%"><input value="<?PHP echo $dealId; ?>" name="dealId" id="dealId" /></td>
	            </tr>
	            <tr>
	                <td width="20%">订单币别:</td>
	                <td width="30%"><input value="<?PHP echo $orderCurrency; ?>" name="orderCurrency" id="orderCurrency" /> </td>
	                <td width="20%">订单金额:</td>
	                <td width="30%"><input value="<?PHP echo $orderAmt; ?>" name="orderAmt" id="orderAmt" /> </td>
	            </tr>
	            <tr>
	                <td width="20%">物流费:</td>
	                <td width="30%"><input value="<?PHP echo $freightAmt; ?>" name="freightAmt" id="freightAmt" /> </td>
	                <td width="20%">货款:</td>
	                <td width="30%"><input value="<?PHP echo $goodsAmt; ?>" name="goodsAmt" id="goodsAmt" /> </td>
	            </tr>
	            <tr>
	                <td width="20%">关税:</td>
	                <td width="30%"><input value="<?PHP echo $taxAmt; ?>" name="taxAmt" id="taxAmt" /> </td>
	                <td width="20%">抵扣金额:</td>
	                <td width="30%"><input value="<?PHP echo $offsetAmt; ?>" name="offsetAmt" id="offsetAmt" /> </td>
	            </tr>
	            <tr>
	                <td width="20%">扩展字段1:</td>
	                <td width="30%"><input value="<?PHP echo $ext1; ?>" name="ext1" id="ext1" /> </td>
	                <td width="20%">扩展字段2:</td>
	                <td width="30%"><input value="<?PHP echo $ext2; ?>" name="ext2" id="ext2" /> </td>
	            </tr>
	            <tr>
	                <td width="20%">业务类型:</td>
	                <td width="30%"><input value="<?PHP echo $bizType; ?>" name="bizType" id="bizType" /> </td>
	            </tr>
				<tr>
				    <td>signMsg:</td>
				    <td><input value="<?PHP echo $signMsg; ?>" name="signMsg" id="signMsg" /> </td>
				</tr>
			</table>
		</div>
		<div align="center" style="font-weight: bold;">
			<table width="800" border="1" style="table-layout:fixed;"  bordercolor="green" align="center">
				<tr>
					<td>
						结果信息:
					</td>
					<td width="90%">
						<?PHP echo $respData; ?>
					</td>
				</tr>
				<tr>
					<td>
						验签结果:
					</td>
					<td>
						<?PHP 
							if ($ok==1) {
								echo "验签成功";
							} else {
								echo "验签失败";
							}
						?>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
