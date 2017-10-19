<?php

function yf_log($file,$txt)
{
	$fp =  fopen($file,'ab+');
	fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
	fwrite($fp,$txt);
	fwrite($fp,"\r\n\r\n\r\n");
	fclose($fp);
}
yf_log('receiveBg.txt',var_export($_REQUEST,true));

	function param_ck_null($kq_va,$kq_na){if($kq_va == ""){return $kq_va="";}else{return $kq_va=$kq_na.'='.$kq_va.'&';}}
	
	//���н��׺� ��ChinaPnr����������֧��ʱ��Ӧ�Ľ��׺ţ��������ͨ�����п�֧������Ϊ��
	$kq_check_all_para=param_ck_null($_REQUEST[bankDealId],'bankDealId');
	//���д��룬���payTypeΪ00����ֵΪ�գ����payTypeΪ10,��ֵ���ύʱ��ͬ��
//	$kq_check_all_para.=param_ck_null($_REQUEST[bankId],'bankId');
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
	
	$signMsgDe=	urldecode($_REQUEST[signMsg]);
	$MAC=base64_decode($signMsgDe);
//yf_log('receiveBg.txt','$MAC:'.$MAC);

	$trans_body_de=urldecode($trans_body);

	$fp = fopen("./ChinaPnR.rsa.pem", "r"); 
	$cert = fread($fp, filesize("./ChinaPnR.rsa.pem")); 
	fclose($fp); 
	$pubkeyid = openssl_get_publickey($cert); 
	$ok = openssl_verify($trans_body_de, $MAC, $pubkeyid); 

	if ($ok == 1) { 
		switch($_REQUEST[payResult]){
				case '10':
						//�˴����̻��߼�����
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
yf_log('receiveBg.txt','yf_$msg:'.$msg);

?>

<result><?PHP echo $msg; ?></result> 

		