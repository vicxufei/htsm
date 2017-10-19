<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<!doctype html>
<html lang="">
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
    <link rel="icon" type="image/png" href="i/favicon.png">
    <?php echo html_entity_decode($output['setting_config']['qq_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'], ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'], ENT_QUOTES); ?>
    <link rel="icon" type="image/png" href="i/favicon.png">
    <!--对于桌面端网页，添加 link rel="alternate" 标签指向相关的移动端地址-->
    <link rel="alternate" media="only screen and (max-width: 640px)" href="http://m.htths.com/">
    <link rel="stylesheet" href="<?php echo STATIC_URL; ?>/css/amazeui.min.css">
    <link rel="stylesheet" href="<?php echo STATIC_URL; ?>/css/main.css">
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
</head>
<body>
<!-- 顶部s -->
<?php require_once template('layout/layout_top'); ?>
<!-- 顶部e -->
<div class="header-wrap">
    <header class="public-head-layout wrapper">
        <div><a class="site-logo" href="<?php echo SHOP_SITE_URL; ?>">太划算商城</a></div>
        <div class="logo-test"><?php echo $output['setting_config']['shopwwi_stitle']; ?></div>

        <div class="head-search-layout">
            <div class="head-search-bar" id="head-search-bar">
                <div class="hd_serach_tab" id="hdSearchTab">
                    <a href="javascript:;" act="search" title="请输入您要搜索的商品关键字" class="d">商品</a>
                    <a href="javascript:;" act="store_list" title="请输入您要搜索的店铺关键字">店铺</a>
                    <i></i>
                </div>
                <form action="<?php echo SHOP_SITE_URL; ?>" method="get" class="search-form" id="top_search_form">
                    <input name="act" id="search_act" value="search" type="hidden">
                    <?php
                    if ($_GET['keyword'])
                    {
                        $keyword = stripslashes($_GET['keyword']);
                    } elseif ($output['rec_search_list'])
                    {
                        $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];
                        $keyword_name = $_stmp['name'];
                        $keyword_value = $_stmp['value'];
                    } else
                    {
                        $keyword = '';
                    }
                    ?>
                    <input name="keyword" id="keyword" type="text" class="input-text" value="<?php echo $keyword; ?>"
                           maxlength="60" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()"
                           placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的商品关键字'; ?>"
                           data-value="<?php echo rawurlencode($keyword_value); ?>" x-webkit-grammar="builtin:search"
                           autocomplete="off"/>
                    <input type="submit" id="button" value="<?php echo $lang['nc_common_search']; ?>"
                           class="input-submit">
                </form>
                <div class="search-tip" id="search-tip">
                    <div class="search-history">
                        <div class="title">历史纪录<a href="javascript:void(0);" id="search-his-del">清除</a></div>
                        <ul id="search-his-list">
                            <?php if (is_array($output['his_search_list']) && !empty($output['his_search_list']))
                            { ?>
                                <?php foreach ($output['his_search_list'] as $v)
                            { ?>
                                <li>
                                    <a href="<?php echo urlShop('search', 'index', array('keyword' => $v)); ?>"><?php echo $v ?></a>
                                </li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="search-hot">
                        <div class="title">热门搜索...</div>
                        <ul>
                            <?php if (is_array($output['rec_search_list']) && !empty($output['rec_search_list']))
                            { ?>
                                <?php foreach ($output['rec_search_list'] as $v)
                            { ?>
                                <li>
                                    <a href="<?php echo urlShop('search', 'index', array('keyword' => $v['value'])); ?>"><?php echo $v['value'] ?></a>
                                </li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="keyword">
                <ul>
                    <?php if (is_array($output['hot_search']) && !empty($output['hot_search']))
                    {
                        foreach ($output['hot_search'] as $val)
                        { ?>
                            <li>
                                <a href="<?php echo urlShop('search', 'index', array('keyword' => $val)); ?>"><?php echo $val; ?></a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
        </div>
        <div class="mod_minicart" style="">
            <a id="nofollow" target="_self" href="<?php echo SHOP_SITE_URL; ?>/index.php?act=cart"
               class="mini_cart_btn">
                <i class="cart_icon"></i>
                <em class="cart_num"><?php echo $output['cart_goods_num']; ?></em>
                <span>购物车</span>
            </a>

            <div id="minicart_list" class="minicart_list">
                <div class="spacer"></div>
                <div class="list_detail">
                    <!--购物车有商品时begin-->
                    <ul><img class="loading" src="<?php echo SHOP_TEMPLATES_URL; ?>/images/loading.gif"/></ul>
                    <div class="checkout_box">
                        <p class="fl">共<em class="tNum"><?php echo $output['cart_goods_num']; ?></em>件商品,合计：<em
                                class="tSum">0</em></p>
                        <a rel="nofollow" class="checkout_btn" href="<?php echo SHOP_SITE_URL; ?>/index.php?act=cart"
                           target="_self"> 去结算 </a>
                    </div>
                    <div style="" class="none_tips">
                        <i> </i>

                        <p>购物车中没有商品，赶紧去选购！</p>
                    </div>
                </div>
            </div>
        </div>
        <!--    <div class="head-user-menu">
      <dl class="my-cart">
        <?php if ($output['cart_goods_num'] > 0)
        { ?>
        <div class="addcart-goods-num"><?php echo $output['cart_goods_num']; ?></div>
        <?php } ?>
        <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4>最新加入的商品</h4>
          </div>
          <div class="incart-goods-box">
            <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_TEMPLATES_URL; ?>/images/loading.gif" /> </div>
          </div>
          <div class="checkout"> <span class="total-price">共<i><?php echo $output['cart_goods_num']; ?></i><?php echo $lang['nc_kindof_goods']; ?></span><a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=cart" class="btn-cart">结算购物车中的商品</a> </div>
        </dd>
      </dl>
    </div>-->
    </header>
</div>
<!-- PublicHeadLayout End -->

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout <?php if ($output['channel'])
{
    echo 'channel-' . $output['channel']['channel_style'] . ' channel-' . $output['channel']['channel_id'];
} ?>">
    <div class="wrapper">
        <div class="all-category">
            <?php require template('layout/home_goods_class'); ?>
        </div>
        <ul class="site-menu">
            <li>
                <a href="<?php echo SHOP_SITE_URL; ?>" <?php if ($output['index_sign'] == 'index' && $output['index_sign'] != '0')
                {
                    echo 'class="current"';
                } ?>><span><?php echo $lang['nc_index']; ?></span></a></li>
            <?php if (C('groupbuy_allow'))
            { ?>
                <li class="navitems-on"><a
                        href="<?php echo urlShop('show_groupbuy', 'index'); ?>" <?php if ($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0')
                    {
                        echo 'class="current"';
                    } ?>> <span><?php echo $lang['nc_groupbuy']; ?></span></a></li>
            <?php } ?>
            <li>
                <a href="<?php echo urlShop('brand', 'index'); ?>" <?php if ($output['index_sign'] == 'brand' && $output['index_sign'] != '0')
                {
                    echo 'class="current"';
                } ?>> <span><?php echo $lang['nc_brand']; ?></span></a></li>
            <li>
                <a href="<?php echo urlShop('promotion', 'index'); ?>" <?php if ($output['index_sign'] == 'promotion' && $output['index_sign'] != '0')
                {
                    echo 'class="current"';
                } ?>> <span>疯抢</span></a></li>
            <?php if (C('points_isuse') && C('pointshop_isuse'))
            { ?>
                <li>
                    <a href="<?php echo urlShop('pointshop', 'index'); ?>" <?php if ($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0')
                    {
                        echo 'class="current"';
                    } ?>> <span><?php echo $lang['nc_pointprod']; ?></span></a></li>
            <?php } ?>
            <?php if (C('cms_isuse'))
            { ?>
                <li>
                    <a href="<?php echo urlShop('special', 'special_list'); ?>" <?php if ($output['index_sign'] == 'special' && $output['index_sign'] != '0')
                    {
                        echo 'class="current"';
                    } ?>> <span>专题</span></a></li>
            <?php } ?>
        </ul>
    </div>
</nav>
