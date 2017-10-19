<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh-CN">
<?php include template1('html5_meta');?>
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/home_cart.min.css" rel="stylesheet" type="text/css" />
<body>
<!-- 顶部s -->
<?php require_once template1('topbar'); ?>
<!-- 顶部e -->
<!-- 头部s -->
<?php require_once template1('site-head2'); ?>
<!-- 头部e -->
<div class="wrapper">
  <?php require_once($tpl_file);?>
</div>
<!-- 底部s -->
<?php require_once template1('footer');?>
<!-- 底部e -->
<!-- 版权s -->
<?php require_once template1('copyright');?>
<!-- 版权e -->
<!--聊天开始-->
<?php echo getChat($layout); ?>
<!--聊天结束-->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<?php if ($_GET['act'] != 'buy_virtual') {?>
  <script src="<?php echo RESOURCE_SITE_URL;?>/pc/js/goods_cart.js"></script>
<?php } else { ?>
  <script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/pc/js/buy_virtual.js"></script>
<?php } ?>
</body>
</html>
