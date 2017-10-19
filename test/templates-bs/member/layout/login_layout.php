<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh-CN">
<?php include template1('html5_meta');?>
<link href="<?php echo RESOURCE_SITE_URL;?>/pc/css/home_login.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/home_header.min.css" rel="stylesheet" type="text/css">
<body>
<div class="site-head">
  <div class="wrapper">
    <div><a class="site-logo" href="<?php echo SHOP_SITE_URL; ?>">太划算商城</a></div>
    <?php if ($output['hidden_login'] != '1') {?>
      <?php if ($_GET['op'] == 'index') {?>
        <div class="register-now">
          <span><?php echo $lang['login_index_regist_now_1'];?><a title="" href="<?php echo urlLogin('login', 'register', array('ref_url' => $_GET['ref_url']));?>" class="register"><?php echo $lang['login_index_regist_now_2'];?></a></span></div>
      <?php } else {?>
        <div class="login-now">
          <?php echo $lang['login_register_login_now_1'];?>
          <a href="<?php echo urlLogin('login', 'index', array('ref_url' => $_GET['ref_url']));?>" title="<?php echo $lang['login_register_login_now'];?>" class="register">
            <?php echo $lang['login_register_login_now_2'];?>
          </a>
          </span>
        </div>
      <?php }?>
    <?php }?>
  </div>
</div>

<!-- PublicHeadLayout End -->
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php require_once($tpl_file);?>
<!-- 版权s -->
<?php require_once template1('copyright');?>
<!-- 版权e -->
<script src="<?php echo LOGIN_RESOURCE_SITE_URL?>/js/taglibs.js"></script>
<script src="<?php echo LOGIN_RESOURCE_SITE_URL?>/js/tabulous.js"></script>
</body>
</html>
