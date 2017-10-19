<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<!doctype html>
<html lang="zh-CN">
<?php include template1('html5_meta');?>
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/home_header.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/index.min.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL; ?>/pc/js/yf1.js"></script>
<body>
<!-- 顶部s -->
<?php require_once template1('topbar'); ?>
<!-- 顶部e -->
<!-- 头部s -->
<?php require_once template1('site-head'); ?>
<!-- 头部e -->
<!-- 导航s -->
<?php include template1('navigation');?>
<!-- 导航e -->
<!-- 面包屑s -->
<?php include template1('crumbs-nav');?>
<!-- 面包屑e -->
<?php require_once($tpl_file);?>
<!-- 底部s -->
<?php require_once template1('footer');?>
<!-- 底部e -->
<!-- 版权s -->
<?php require_once template1('copyright');?>
<!-- 版权e -->
<!-- 工具栏s-->
<?php if ($output['hidden_nctoolbar'] != 1){ ?>
    <div id="appbar" class="appbar">
        <div class="appbar-tabs" id="appbar_tabs">
            <div class="appbar-tabs-middle">
                <?php if (!$output['hidden_rtoolbar_cart']){ ?>
                    <div class="cart">
                        <a href="javascript:void(0);" id="appbar_cart">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="name">购物车</span>
                            <span id="appbar_cart_count"
                               class="new_msg"
                               style="display:none;">
                            </span>
                        </a>
                    </div>
                <?php } ?>

                <?php if (C('node_chat')){ ?>
                    <div class="chat">
                        <a href="javascript:store_chat(1,'售前1');">
                            <i class="fa fa-comment"></i>
                            <div id="new_msg" class="new_msg" style="display:none;"></div>
                            <span class="tit">在线联系</span>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="appbar-tab-bottom">
                <div class="l_qrcode">
                    <a href="javascript:void(0);" class="">
                        <i class="fa fa-qrcode"></i>
                        <code>
                            <img src="http://www.htths.com/js/suspension/htsm.jpg">
                        </code>
                    </a>
                </div>
                <div class="gotop">
                    <a href="javascript:void(0);" id="gotop">
                        <i class="fa fa-arrow-circle-up"></i>
                        <span class="tit">返回顶部</span>
                    </a>
                </div>
            </div>
            <div class="appbar-content-box" id="content-cart">
                <div class="top">
                    <h3>我的购物车</h3>
                    <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
                <div id="appbar_cartlist"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            <?php if ($output['cart_goods_num'] > 0) { ?>
            $('#appbar_cart_count').html(<?php echo $output['cart_goods_num'];?>).show();
            <?php } ?>
        });
    </script>
<?php } ?>
<!--工具栏 e-->
<!--聊天开始-->
<?php echo getChat($layout); ?>
<!--聊天结束-->
</body>
</html>
