<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller_center.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/order_print.css" rel="stylesheet" type="text/css" media="print">
<style type="text/css">
body { background: #FFF none;
}
    .order-info tr{
        border-bottom: solid 1px #000;
    }
</style>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/jquery.printarea.js" charset="utf-8"></script>
<title><?php echo $lang['member_printorder_print'];?>--<?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></title>
</head>

<body>
<?php if (!empty($output['order_info'])){?>
  <div class="print-page">
    <div id="printarea">
      <div class="orderprint">
        <div class="top">
          <?php if (empty($output['store_info']['store_label'])){?>
          <div class="full-title"><?php echo $output['store_info']['store_name'];?> <?php echo $lang['member_printorder_title'];?></div>
          <?php }else {?>
          <div class="logo" ><img src="<?php echo $output['store_info']['store_label']; ?>"/></div>
          <div class="logo-title"><?php echo $output['store_info']['store_name'];?><?php echo $lang['member_printorder_title'];?></div>
          <?php }?>
        </div>
        <table class="buyer-info">
          <tr>
            <td class="w200"><?php echo $lang['member_printorder_truename'].$lang['nc_colon']; ?><?php echo $output['order_info']['extend_order_common']['reciver_name'];?><?php  if($output['order_info']['chain_id'] > 0){echo $output['order_info']['buyer_name'];} ?></td>
            <td class="w200"><?php echo '电话'.$lang['nc_colon']; ?><?php echo @$output['order_info']['extend_order_common']['reciver_info']['phone'];?><?php  if($output['order_info']['chain_id'] > 0){echo $output['order_info']['buyer_phone'];} ?></td>
            <td class="w200">下单:<?php echo @date('Y-m-d H:i',$output['order_info']['add_time']);?></td>
            <td class="w200">订单号:<?php echo $output['order_info']['order_sn'];?></td>
            <?php if ($output['order_info']['chain_id'] > 0){?>
              <td class="w200">提货码:<?php echo $output['order_info']['chain_code'];?></td>
            <?php }?>
          </tr>
          <?php if ($output['order_info']['chain_id'] == 0){?>
          <tr>
            <td colspan="3"><?php echo $lang['member_printorder_address'].$lang['nc_colon']; ?><?php echo @$output['order_info']['extend_order_common']['reciver_info']['address'];?></td>
            <?php if ($output['order_info']['extend_order_common']['order_message']){?>
              <td class="w200">用户留言:<?php echo $output['order_info']['extend_order_common']['order_message'];?></td>
            <?php }?>
          </tr>
          <?php }?>
          <tr>
            <td><?php if ($output['order_info']['shippin_code']){?>
              <span><?php echo $lang['member_printorder_shippingcode'].$lang['nc_colon']; ?><?php echo $output['order_info']['shipping_code'];?></span>
              <?php }?></td>
          </tr>
        </table>
        <table class="order-info">
          <thead>
            <tr>
              <th class="w40"><?php echo $lang['member_printorder_serialnumber'];?></th>
                <th class="w100 tl">条形码</th>
              <th class="tl"><?php echo $lang['member_printorder_goodsname'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printorder_goodsprice'];?>(<?php echo $lang['currency_zh'];?>)</th>
              <th class="w50"><?php echo $lang['member_printorder_goodsnum'];?></th>
              <th class="w70 tl"><?php echo $lang['member_printorder_subtotal'];?>(<?php echo $lang['currency_zh'];?>)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($output['goods_list'] as $k=>$v){?>
            <tr>
              <td><?php echo ++$k;?></td>
              <td class="tl"><?php echo $v['goods_barcode'];?></td>
              <td class="tl"><?php echo $v['goods_name'];?></td>
              <td class="tl"><?php echo $lang['currency'].ncPriceFormat($v['goods_price']);?></td>
              <td><?php echo $v['goods_num'];?></td>
              <td class="tl"><?php echo $lang['currency'].$v['goods_all_price'];?></td>
            </tr>
            <?php }?>
            <tr>
              <th></th>
              <th></th>
              <th colspan="2" class="tl"><?php echo $lang['member_printorder_amountto'];?></th>
              <th><?php echo $output['goods_all_num'];?></th>
              <th class="tl"><?php echo $lang['currency'].$output['goods_total_price'];?></th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="10">
                  <span>配送方式:<?php  if($output['order_info']['chain_id'] == 1){echo "太仓店自提";}
                  elseif($output['order_info']['chain_id'] == 6){echo '沙溪自提点';}
                  elseif($output['order_info']['chain_id'] == 7){echo '浏河自提点';}
                  elseif($output['order_info']['chain_id'] == 8){echo '浏家港自提点';}
                  elseif($output['order_info']['chain_id'] == 9){echo '浮桥自提点';}
                  elseif($output['order_info']['chain_id'] == 13){echo '陆渡自提点';}
                  elseif($output['order_info']['chain_id'] == 12){echo '直塘自提点';}
                  elseif($output['order_info']['chain_id'] == 14){echo '牌楼自提点';}
                  else{echo '快递配送';};?></span>
                  <span>支付方式:<?php echo $output['order_info']['payment_name']; ?></span>
                  <span><?php echo $lang['member_printorder_totle'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['goods_total_price'];?></span>
                  <span><?php echo $lang['member_printorder_freight'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info']['shipping_fee'];?></span>
                  <!--<span>--><?php //echo $lang['member_printorder_privilege'].$lang['nc_colon'];?><!----><?php //echo $lang['currency'].$output['promotion_amount'];?><!--</span>-->
                  <span><?php echo $lang['member_printorder_orderamount'].$lang['nc_colon'];?><?php echo $lang['currency'].$output['order_info']['order_amount'];?></span>
                  <!--<span>--><?php //echo $lang['member_printorder_shop'].$lang['nc_colon'];?><!----><?php //echo $output['store_info']['store_name'];?><!--</span>-->
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <?php }?>
  </div>
</body>
<script>
$(function(){
	$("#printbtn").click(function(){
        console.log($("#printarea"));
	$("#printarea").printArea({mode:'popup'});
    });
});

//打印提示
$('#printbtn').poshytip({
	className: 'tip-yellowsimple',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	alignY: 'bottom',
	offsetY: 5,
	allowTipHover: false
});
</script>
</html>