<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<?PHP

	//人民币网关账号，该账号为11位人民币网关商户编号+01,该参数必填。
	$merchantAcctId = "1000526000101";
	$terminalId = "10001";
	//编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312 默认为1,该参数必填。
	$inputCharset = "1";
	//接收支付结果的页面地址，该参数一般置为空即可。
	$pageUrl = "http://localhost/receivePg.php";
	//服务器接收支付结果的后台地址，该参数务必填写，不能为空。
	$bgUrl = "http://www.htths.com/php-esaypay/receiveBg.php";
	//网关版本，固定值：hw1.0,该参数必填。
	$version =  "3.0";
	//语言种类，1代表中文显示，2代表英文显示。默认为1,该参数必填。
	$language =  "1";
	//签名类型,该值为4，代表PKI加密方式,该参数必填。
	$signType =  "4";
	//支付人姓名,可以为空。
	$payerName= "李财水"; 
	//支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空。
	$payerContactType =  "2";
	//支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
	$payerContact =  "";
	$payerIdentityCard =  "320125198805232313";
	$mobileNumber =  "13686854601";
	$cardNumber =  "";
	//必填
	$customerId =  "abcd";
	
	//商户订单号，以下采用时间来定义订单号，商户可以根据自己订单号的定义规则来定义该值，不能为空。
	$orderId = date("YmdHis");
	//询盘流水号
	$inquireTrxNo = "";
	$orderCurrency = "USD";
	$settlementCurrency = "USD";
	//订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试。该参数必填。
	$orderAmount = "1040";
	//订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101，不能为空。
	$orderTime = date("YmdHis");
	//商品名称，可以为空。
	$productName= "iphone18"; 
	//商品数量，可以为空。
	$productNum = "1";
	//商品代码，可以为空。
	$productId = "YN0001";
	//商品描述，可以为空。
	$productDesc = "T";
	//扩展字段1，商户可以传递自己需要的参数，支付完Atimes会原值返回，可以为空。
	$ext1 = "ext1";
	//扩展自段2，商户可以传递自己需要的参数，支付完Atimes会原值返回，可以为空。
	$ext2 = "ext2";
	//支付方式，30，必填。
	$payType = "30";
	//银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表。
	$bankId = "";
	$refererUrl ="";
	$customerIp = "";
	$orderTimeout = "";
	$divDetails = "";
	//同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空。
	$redoFlag = "1";
	// signMsg 签名字符串 不可空，生成加密签名串
	$pid = "";

	function param_ck_null($kq_va,$kq_na){
		if($kq_va == ""){
			$kq_va="";
		}else{
			return $kq_va=$kq_na.'='.$kq_va.'&';
		}
	}


	$kq_all_para=param_ck_null($inputCharset,'inputCharset');
	$kq_all_para.=param_ck_null($pageUrl,"pageUrl");
	$kq_all_para.=param_ck_null($bgUrl,'bgUrl');
	$kq_all_para.=param_ck_null($version,'version');
	$kq_all_para.=param_ck_null($language,'language');
	$kq_all_para.=param_ck_null($signType,'signType');
	$kq_all_para.=param_ck_null($merchantAcctId,'merchantAcctId');
	$kq_all_para.=param_ck_null($terminalId,'terminalId');
	$kq_all_para.=param_ck_null($payerName,'payerName');
	$kq_all_para.=param_ck_null($payerContactType,'payerContactType');
	$kq_all_para.=param_ck_null($payerContact,'payerContact');
	$kq_all_para.=param_ck_null($payerIdentityCard,'payerIdentityCard');
	$kq_all_para.=param_ck_null($mobileNumber,'mobileNumber');
	$kq_all_para.=param_ck_null($cardNumber,'cardNumber');
	$kq_all_para.=param_ck_null($customerId,'customerId');
	$kq_all_para.=param_ck_null($orderId,'orderId');
	$kq_all_para.=param_ck_null($inquireTrxNo,'inquireTrxNo');
	$kq_all_para.=param_ck_null($orderCurrency,'orderCurrency');
	$kq_all_para.=param_ck_null($settlementCurrency,'settlementCurrency');
	$kq_all_para.=param_ck_null($orderAmount,'orderAmount');
	$kq_all_para.=param_ck_null($orderTime,'orderTime');
	$kq_all_para.=param_ck_null($productName,'productName');
	$kq_all_para.=param_ck_null($productNum,'productNum');
	$kq_all_para.=param_ck_null($productId,'productId');
	$kq_all_para.=param_ck_null($productDesc,'productDesc');
	$kq_all_para.=param_ck_null($ext1,'ext1');
	$kq_all_para.=param_ck_null($ext2,'ext2');
	$kq_all_para.=param_ck_null($payType,'payType');
	$kq_all_para.=param_ck_null($bankId,'bankId');
	$kq_all_para.=param_ck_null($refererUrl,'refererUrl');
	$kq_all_para.=param_ck_null($customerIp,'customerIp');
	$kq_all_para.=param_ck_null($orderTimeout,'orderTimeout');
	$kq_all_para.=param_ck_null($divDetails,'divDetails');
	$kq_all_para.=param_ck_null($redoFlag,'redoFlag');
	

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

	
	/////////////  后台置单 ///////// 开始 //	
	$post_data = array (
		"inputCharset" => $inputCharset,
		"pageUrl" => $pageUrl,
		"bgUrl" => $bgUrl,
		"version" => $version,
		"language" => $language,
		"signType" => $signType,
		"merchantAcctId" => $merchantAcctId,
		"terminalId" => $terminalId,
		"payerName" => $payerName,
		"payerContactType" => $payerContactType,
		"payerContact" => $payerContact,
		"payerIdentityCard" => $payerIdentityCard,
		"mobileNumber" => $mobileNumber,
		"cardNumber" => $cardNumber,
		"customerId" => $customerId,
		"orderId" => $orderId,
		"inquireTrxNo" => $inquireTrxNo,
		"orderCurrency" => $orderCurrency,
		"settlementCurrency" => $settlementCurrency,
		"orderAmount" => $orderAmount,
		"orderTime" => $orderTime,
		"productName" => $productName,
		"productNum" => $productNum,
		"productId" => $productId,
		"productDesc" => $productDesc,
		"ext1" => $ext1,
		"ext2" => $ext2,
		"payType" => $payType,
		"bankId" => $bankId,
		"refererUrl" => $refererUrl,
		"customerIp" => $customerIp,
		"orderTimeout" => $orderTimeout,
		"divDetails" => $divDetails,
		"redoFlag" => $redoFlag,
		"signMsg" => $signMsg
	);
	
	$url = 'https://mertest.chinapnr.com/pay/recvquickpay.htm';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);// 设置 POST 参数
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//不验证证书
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//不验证证书
	$respData = curl_exec($curl);
	$respUrl = "";
	curl_close($curl);
	
	/////////////  后台置单 ///////// 结束//	
	
	if (strpos($respData,"errCode")) {
		echo $respData;
	}else{
		$respUrl = $respData;
	}
?>

<style type="text/css">
	td{text-align:center}
</style>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title></title>
	
	</head>
	<body>
		<div align="center">
			<h2 align="center">提交到ChinaPnr页面</h2>
			<font color="#ff0000">（该页面仅做参考）</font>
    		<table width="500" border="1" style="border-collapse: collapse" bordercolor="green" align="center">
				<tr>
					<td id="orderId">
						订单号
					</td>
					<td>
						<?PHP echo $orderId; ?>
					</td>
				</tr>
				<tr>
					<td id="orderAmount">
						订单金额
					</td>
					<td>
						<?PHP echo $orderAmount; ?>
					</td>
				</tr>
				<tr>
					<td id="orderTime">
						下单时间
					</td>
					<td>
						<?PHP echo $orderTime; ?>
					</td>
				</tr>
				<tr>
					<td id="productName">
						商品名称
					</td>
					<td>
						<?PHP echo $productName; ?>
					</td>
				</tr>
				<tr>
					<td id="productNum">
						商品数量
					</td>
					<td>
						<?PHP echo $productNum; ?>
					</td>
				</tr>
			</table>
		</div>
		<div align="center" style="font-weight: bold;">
			<form name="kqPay" action="<?PHP echo $respUrl; ?>" method="post">
				<input type="submit" name="submit" value="前往ChinaPnr支付">
			</form>
		</div>
	</body>
</html>
