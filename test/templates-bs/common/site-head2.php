<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div class="site-head">
    <div class="wrapper">
        <div><a class="site-logo" href="<?php echo SHOP_SITE_URL; ?>">太划算商城</a></div>
        <?php if ($_GET['op'] != 'pd_pay' && $_POST['payment_code'] != 'wxpay')
        { ?>
            <ul class="cart-flow">
                <li class="<?php echo $output['buy_step'] == 'step1' ? 'current' : ''; ?>">
                    <p><?php echo $lang['cart_index_ensure_order']; ?></p>
                    <sub></sub>
                    <div class="hr"></div>
                </li>
                <li class="<?php echo $output['buy_step'] == 'step2' ? 'current' : ''; ?>">
                    <p><?php echo $lang['cart_index_ensure_info']; ?></p>
                    <sub></sub>
                    <div class="hr"></div>
                </li>
                <li class="<?php echo $output['buy_step'] == 'step3' ? 'current' : ''; ?>">
                    <p><?php echo $lang['cart_index_payment']; ?></p>
                    <sub></sub>
                    <div class="hr"></div>
                </li>
                <li class="<?php echo $output['buy_step'] == 'step4' ? 'current' : ''; ?>">
                    <p><?php echo $lang['cart_index_buy_finish']; ?></p>
                    <sub></sub>
                    <div class="hr"></div>
                </li>
            </ul>
        <?php } ?>
    </div>
</div>