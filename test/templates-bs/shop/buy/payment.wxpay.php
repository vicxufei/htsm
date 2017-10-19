<?php defined('ByShopWWI') or exit('Access Invalid!'); ?>
<div class="cart-title">
    <p>订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。</p>
</div>
<div class="receipt-info">
    <h3> 订单提交成功，请您尽快付款。 应付金额：<strong><?php echo ncPriceFormat($output['api_pay_amount']); ?></strong>元 </h3>
</div>
<table class="cart-table">
    <thead>
    <tr>
        <th class="w50"></th>
        <th class="w200 tl">订单号</th>
        <th class="tl">金额(元)</th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($output['order_list']) > 1){ ?>
    <tr>
        <th colspan="20">由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']); ?>个不同子订单配送！</th>
    </tr>
    <?php } ?>
    <?php foreach ($output['order_list'] as $key => $order_info){ ?>
    <tr>
        <td></td>
        <td class="tl"><?php echo $order_info['order_sn']; ?></td>
        <td class="tl"><?php echo $order_info['order_amount']; ?></td>
    </tr>
    <?php } ?>
    </tbody>
</table>
<div class="wxpayment">
    <div class="weixin-pay">
        <h3>微信支付</h3>
        <div class="wxpay-bd">
            <img src="<?php echo SHOP_SITE_URL ?>/index.php?act=payment&op=qrcode&data=<?php echo urlencode($output['pay_url']); ?>">
            <div class="wxpay-ft">
                <p>请使用微信扫一扫</p>
                <p>扫描二维码支付</p>
            </div>
        </div>
        <div class="wxpay-sidebar"></div>
    </div>
    <div class="payment-change">
        <a href="javascript:history.back(-1)" id="reChooseUrl" class="pc-wrap">
            <i class="pc-w-arrow-left">&lt;</i>
            <strong>选择其他支付方式</strong> </a>
    </div>
</div>
<script>
    $(document).ready(function () {
        setInterval(queryOrderState, 3000);
    });
    function queryOrderState() {
        $.ajax({
            type: "GET",
            url: "<?php echo SHOP_SITE_URL?>/index.php?act=payment&op=query_state&<?php echo $output['args'];?>",
            data: "",
            dataType: "json",
            timeout: 4000,
            async: false,
            success: function (result) {
                if (result.state == 1) {
                    if (result.type == 'r') {
                        window.location.href = '<?php echo SHOP_SITE_URL.'/index.php?act=member_order'?>';
                    } else {
                        window.location.href = '<?php echo SHOP_SITE_URL.'/index.php?act=member_vr_order&op=index'?>';
                    }
                }
            }
        });
    }
</script>
