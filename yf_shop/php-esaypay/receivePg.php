<?php
header("Content-type: text/html; charset=utf-8");


function param_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
	
	//���н��׺� ��ChinaPnr����������֧��ʱ��Ӧ�Ľ��׺ţ��������ͨ�����п�֧������Ϊ��
	$kq_check_all_para=param_ck_null($_REQUEST[bankDealId],'bankDealId');
	//���д��룬���payTypeΪ00����ֵΪ�գ����payTypeΪ10,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[bankId],'bankId');
	// ChinaPnr���׺ţ��̻�ÿһ�ʽ��׶�����ChinaPnr����һ�����׺š�
	$kq_check_all_para.=param_ck_null($_REQUEST[dealId],'dealId');
	//ChinaPnr����ʱ�䣬ChinaPnr�Խ��׽��д����ʱ��,��ʽ��yyyyMMddHHmmss���磺20071117020101
	$kq_check_all_para.=param_ck_null($_REQUEST[dealTime],'dealTime');
	//������� ������ա���������ؽӿ��ĵ�����󲿷ֵ���ϸ���͡�
	$kq_check_all_para.=param_ck_null($_REQUEST[errCode],'errCode');
	//��չ�ֶ�1����ֵ���ύʱ��ͬ
	$kq_check_all_para.=param_ck_null($_REQUEST[ext1],'ext1');
	//��չ�ֶ�2����ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[ext2],'ext2');
	//����������˺ţ����˺�Ϊ11λ����������̻����+01,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[merchantAcctId],'merchantAcctId');
	//����������ԡ��֡�Ϊ��λ���̻�������1�ֲ��Լ��ɣ������Դ������,��ֵ��֧��ʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[orderAmount],'orderAmount');
	$kq_check_all_para.=param_ck_null($_REQUEST[orderCurrency],'orderCurrency');
	//�̻������ţ�,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[orderId],'orderId');
	//�����ύʱ�䣬��ʽ��yyyyMMddHHmmss���磺20071117020101,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[orderTime],'orderTime');
	//�������� 10֧���ɹ���11 ֧��ʧ�ܣ�00��������ɹ���01 ��������ʧ��
	$kq_check_all_para.=param_ck_null($_REQUEST[payResult],'payResult');
	//֧����ʽ��һ��Ϊ00���������е�֧����ʽ�����������ֱ���̻�����ֵΪ10,��ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[payType],'payType');
	//�̻��ն˺ţ���ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[terminalId],'terminalId');
	//���ذ汾����ֵ���ύʱ��ͬ��
	$kq_check_all_para.=param_ck_null($_REQUEST[version],'version');

	$trans_body=substr($kq_check_all_para,0,strlen($kq_check_all_para)-1);

	$MAC=base64_decode($_REQUEST[signMsg]);

	$fp = fopen("./ChinaPnR.rsa.pem", "r"); 
	$cert = fread($fp, filesize("./ChinaPnR.rsa.pem")); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body, $MAC, $pubkeyid); 

	//����������ChinaPnr���õ�showҳ�棬�̻���Ҫ�Լ������ҳ�档
	$rtnUrl="http://www.htths.com/php-esaypay/show.php";
	
	if ($ok == 1) { 
		switch($_REQUEST[payResult]){
				case '10':
						//�˴����̻��߼�����
						$rtnOK=1;
						$msg="success";
						break;
				default:
						$rtnOK=0;
						$msg="false";
						break;	
		
		}

	}else{
						$rtnOK=0;
						$msg="error";
	}

?>

<result><?PHP echo $rtnOK; ?></result> <redirecturl><?PHP echo $rtnUrl; ?></redirecturl>

	<form name="kqPay" action="<?PHP echo $rtnUrl; ?>" method="post">
			<input type="hidden" name="dealId" value="<?PHP echo $_REQUEST['dealId'] ?>" />
			<input type="hidden" name="orderId" value="<?PHP echo $_REQUEST['orderId'] ?>" />
			<input type="hidden" name="orderAmount" value="<?PHP echo $_REQUEST['orderAmount'] ?>" />
			<input type="hidden" name="orderTime" value="<?PHP echo $_REQUEST['orderTime'] ?>" />
			<input type="hidden" name="msg" value="<?PHP echo $msg; ?>"/>
			<input type="submit" name="submit" value="��ʾ���">
		</form>
		