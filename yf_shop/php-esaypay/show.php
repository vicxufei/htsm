
<!doctype html public "-//w3c//dtd html 4.0 transitional//en" >
<html>
	<head>
		<title>ChinaPnr֧�����</title>
			<meta http-equiv=Content-Type content="text/html;charset=utf-8">
		<style type="text/css">
			td{text-align:center}
		</style>
	</head>
	
<BODY>
	<div align="center">
		<h2 align="center">ChinaPnr֧�����ҳ��</h2>
		<font color="#ff0000">����ҳ������ο���</font>
    	<table width="500" border="1" style="border-collapse: collapse" bordercolor="green" align="center">
			<tr>
				<td id="dealId">
					ChinaPnr���׺�
				</td>
				<td>
					<?PHP echo $_REQUEST['dealId']; ?>
				</td>
			</tr>
			<tr>
				<td id="orderId">
					������
				</td>
				<td>
					<?PHP echo $_REQUEST['orderId']; ?>
				</td>
			</tr>
			<tr>
				<td id="orderAmount">
					�������
				</td>
				<td>
					<?PHP echo $_REQUEST['orderAmount']; ?>
				</td>
			</tr>
			<tr>
				<td id="orderTime">
					�µ�ʱ��
				</td>
				<td>
					<?PHP echo $_REQUEST['orderTime']; ?>
				</td>
			</tr>
			<tr>
				<td id="payResult">
					������
				</td>
				<td>
					<?PHP echo $_REQUEST['msg']; ?>
				</td>
			</tr>	
		</table>
	</div>
</BODY>
</HTML>