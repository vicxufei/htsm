<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<!doctype html>
<html lang="zh">
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
    <link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/base.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/home_header.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/font-awesome.min.css" rel="stylesheet"/>
    <!--仅首页?-->
    <link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/index.min.css" rel="stylesheet" type="text/css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
    <![endif]-->
    <!-- Respond.js IE8 support of media queries -->
    <!--[if lt IE 9]>
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
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/common.js" charset="utf-8"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/jquery.ui.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL; ?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
    <script type="text/javascript">
        var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
        $(function () {
            $(".mod_minicart").hover(function () {
                    $("#nofollow,#minicart_list").addClass("on");
                },
                function () {
                    $("#nofollow,#minicart_list").removeClass("on");
                });
            $('.mod_minicart').mouseover(function () {// 运行加载购物车
                load_cart_information();
                $(this).unbind('mouseover');
            });
            <?php if (C('fullindexer.open')) { ?>
            // input ajax tips
            $('#keyword').focus(function () {
                if ($(this).val() == $(this).attr('title')) {
                    $(this).val('').removeClass('tips');
                }
            }).blur(function () {
                if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
                    $(this).addClass('tips').val($(this).attr('title'));
                }
            }).blur().autocomplete({
                source: function (request, response) {
                    $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                        $('#top_search_box > ul').unwrap();
                        response(data);
                        if (status == 'success') {
                            $('body > ul:last').wrap("<div id='top_search_box'></div>").css({
                                'zIndex': '1000',
                                'width': '362px'
                            });
                        }
                    });
                },
                select: function (ev, ui) {
                    $('#keyword').val(ui.item.label);
                    $('#top_search_form').submit();
                }
            });
            <?php } ?>

            $('#button').click(function () {
                if ($('#keyword').val() == '') {
                    if ($('#keyword').attr('data-value') == '') {
                        return false
                    } else {
                        window.location.href = "<?php echo SHOP_SITE_URL?>/index.php?act=search&op=index&keyword=" + $('#keyword').attr('data-value');
                        return false;
                    }
                }
            });
            $(".head-search-bar").hover(null,
                function () {
                    $('#search-tip').hide();
                });
            // input ajax tips
            $('#keyword').focus(function () {
                $('#search-tip').show()
            }).autocomplete({
                //minLength:0,
                source: function (request, response) {
                    $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                        $('#top_search_box > ul').unwrap();
                        response(data);
                        if (status == 'success') {
                            $('#search-tip').hide();
                            $(".head-search-bar").unbind('mouseover');
                            $('body > ul:last').wrap("<div id='top_search_box'></div>").css({
                                'zIndex': '1000',
                                'width': '362px'
                            });
                        }
                    });
                },
                select: function (ev, ui) {
                    $('#keyword').val(ui.item.label);
                    $('#top_search_form').submit();
                }
            });
            $('#search-his-del').on('click', function () {
                $.cookie('<?php echo C('cookie_pre')?>his_sh', null, {path: '/'});
                $('#search-his-list').empty();
            });
        });
    </script>
</head>
<body>
<!-- topbar顶部s -->
<?php require_once template('layout/layout_top'); ?>
<!-- topbar顶部e -->
<!-- site-head头部s -->
<div class="site-head">
    <div class="wrapper">
        <div><a class="site-logo" href="<?php echo SHOP_SITE_URL; ?>">太划算商城</a></div>
        <div class="slogan"><?php echo $output['setting_config']['shopwwi_stitle']; ?></div>

        <div class="head-search-layout">
            <div class="head-search-bar" id="head-search-bar">
                <form action="<?php echo SHOP_SITE_URL; ?>" method="get" id="top_search_form">
                    <?php if ($_GET['keyword'])
                    {
                        $keyword = stripslashes($_GET['keyword']);
                    }
                    elseif ($output['rec_search_list'])
                    {
                        $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];
                        $keyword_name = $_stmp['name'];
                        $keyword_value = $_stmp['value'];
                    }
                    else
                    {
                        $keyword = '';
                    } ?>
                    <input name="keyword" id="keyword" type="text" class="input-text" value="<?php echo $keyword; ?>"
                           maxlength="60" placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的商品关键字'; ?>"
                           data-value="<?php echo rawurlencode($keyword_value); ?>" autocomplete="off"/>
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
            <div class="kw-suggest">
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
    </div>
</div>
<!-- site-head头部e -->
<!-- publicNavLayout Begin -->
<div class="navigation">
    <div class="wrapper">
        <!--    --><?php //require template('layout/home_goods_class'); ?>

        <ul class="nav-cnt">
            <?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])){ ?>
            <li><a href="<?php echo SHOP_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><?php echo $lang['nc_index'];?></a></li>
                <?php foreach ($output['show_goods_class'] as $key => $val){ ?>
            <li cat_id="<?php echo $val['gc_id']; ?>">
                <a href=" <?php if (!empty($val['channel_id']))
                {
                    echo urlShop('channel', 'index', array('id' => $val['channel_id']));
                }
                else
                {
                    echo urlShop('search', 'index', array('cate_id' => $val['gc_id']));
                } ?>"><?php echo $val['gc_name']; ?></a>

                <div class="pin-down jPinDown jNavLoad pin-down-6" cat_menu_id="<?php echo $val['gc_id']; ?>">
                    <div class="pin-down-cate">
                        <?php if (!empty($val['class2']) && is_array($val['class2'])){ ?>
                            <?php foreach ($val['class2'] as $k => $v){ ?>
                                <dl class="item-list">
                                <dt>
                                    <a href="<?php echo urlShop('search', 'index', array('cate_id' => $v['gc_id'])); ?>"><?php echo $v['gc_name']; ?></a>
                                </dt>
                                <dd><?php if (!empty($v['class3']) && is_array($v['class3'])){ ?>
                                        <?php foreach ($v['class3'] as $k3 => $v3){ ?>
                                            <a href="<?php echo urlShop('search', 'index', array('cate_id' => $v3['gc_id'])); ?>">
                                            <?php echo $v3['gc_name']; ?></a>
                                        <?php }} ?>
                                </dd>
                                </dl>
                            <?php }} ?>
                    </div>
                    <div class="sub-class-right"><?php if (!empty($val['cn_brands']))
                        { ?>
                            <div class="brands-list">
                            <ul><?php foreach ($val['cn_brands'] as $brand)
                                { ?>
                                    <li> <a
                                        href="<?php echo urlShop('brand', 'list', array('brand' => $brand['brand_id'])); ?>"
                                        title="<?php echo $brand['brand_name']; ?>"><?php if ($brand['brand_pic'] != '')
                                        { ?><img src="<?php echo brandImage($brand['brand_pic']); ?>"/><?php } ?>
                                        <span><?php echo $brand['brand_name']; ?></span></a></li><?php } ?></ul>
                            </div><?php } ?>
                        <div class="adv-promotions"><?php if ($val['cn_adv1'] != '')
                            { ?>
                            <a <?php echo $val['cn_adv1_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="' . $val['cn_adv1_link'] . '"'; ?>>
                                <img src="<?php echo $val['cn_adv1']; ?>"></a><?php } ?><?php if ($val['cn_adv2'] != '')
                            { ?>
                            <a <?php echo $val['cn_adv2_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="' . $val['cn_adv2_link'] . '"'; ?>>
                                <img src="<?php echo $val['cn_adv2']; ?>"></a><?php } ?></div>
                    </div>
                </div></li><?php }
            } ?>
        </ul>
    </div>
</div>
