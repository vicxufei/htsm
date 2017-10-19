<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $output['html_title'];?></title>
<link href="<?php echo CHAIN_TEMPLATES_URL?>/css/chain.css" rel="stylesheet" type="text/css">
<link href="<?php echo CHAIN_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo CHAIN_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var CHAIN_RESOURCE_SITE_URL = '<?php echo CHAIN_RESOURCE_SITE_URL;?>';var CHAIN_TEMPLATES_URL = '<?php echo CHAIN_TEMPLATES_URL;?>';</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
</head>

<body>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ToolTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<header class="ncsc-head-layout w">
  <div class="wrapper">
    <div class="ncsc-admin">
    <dl><dt><?php echo $_SESSION['chain_name'];?></dt>
    <dd><?php echo $_SESSION['chain_address'];?></dd>
    <dd><?php echo $_SESSION['chain_phone']?></dd></dl><div class="pic"><img src="<?php echo $_SESSION['chain_img'];?>"></div>
    </div>
    <?php if ($_SESSION['chain_user'] == 'ht_tc') { ?>
      <div class="center-logo">
        <a target="_blank" href="index.php?act=goods&op=sync_stock" class="ncbtn ncbtn-mint" title="更新收银机库存">收银机流水更新</a>
        <a
          target="_blank"
          href="index.php?act=goods&op=update_stock"
          class="ncbtn ncbtn-mint"
          title="更新收银机库存"
        >调拨库存后更新</a>
      </div>
      <div style="float:right;line-height: 60px">
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=1">太仓</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=6">沙溪</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=7">浏河</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=8">浏家港</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=9">浮桥</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=13">陆渡</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=12">直塘</a>
        <a class="ncbtn ncbtn-mint" href="index.php?act=login&op=index&chain_id=14">牌楼</a>
      </div>
    <?php } ?>
    <ul class="ncsc-nav">
      <?php if ($_SESSION['chain_user'] == 'ht_tc') { ?>
      <li> <a href="index.php?act=goods" class="<?php echo $_GET['act'] == 'goods'?'current':'';?>">门店商品库存</a> </li>
      <?php } ?>
      <li> <a href="index.php?act=order" class="<?php echo $_GET['act'] == 'order'?'current':'';?>">门店取货订单</a> </li>
      <li><a href="javascript:;" onclick="ajaxget('<?php echo urlChain('login', 'logout');?>')" title="安全退出">安全退出</a></li>
    </ul>
  </div>
</header>
<div class="ncsc-layout wrapper">
  <div class="main-content" id="mainContent">
    <?php require_once($tpl_file); ?>
  </div>
  <div id="layoutRight" class="ncsc-layout-right"> </div>
</div>
<?php require_once template('layout/footer');?>
</body>
</html>
