<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<head>
    <meta charset="<?php echo CHARSET; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="<?php echo $output['seo_keywords']; ?>"/>
    <meta name="description" content="<?php echo $output['seo_description']; ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $output['html_title']; ?></title>
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <?php echo html_entity_decode($output['setting_config']['qq_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'], ENT_QUOTES); ?>
    <!--对于桌面端网页，添加 link rel="alternate" 标签指向相关的移动端地址-->
    <link rel="alternate" media="only screen and (max-width: 640px)" href="http://m.htths.com/">
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo STATIC_URL; ?>/css/base.min.css" />
    <link rel="stylesheet" href="<?php echo STATIC_URL; ?>/css/gyf.min.css" />
    <link rel="stylesheet" href="<?php echo STATIC_URL; ?>/css/good-item.min.css" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
    <![endif]-->
    <script>
        var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';
        var _CHARSET = '<?php echo strtolower(CHARSET);?>';
        var LOGIN_SITE_URL = '<?php echo LOGIN_SITE_URL;?>';
        var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';
        var SITEURL = '<?php echo SHOP_SITE_URL;?>';
        var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
        var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
        var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
    </script>
    <!--<script src="--><?php //echo RESOURCE_SITE_URL; ?><!--/pc/js/jquery.js"></script>-->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/pc/js/common.js"></script>
</head>

