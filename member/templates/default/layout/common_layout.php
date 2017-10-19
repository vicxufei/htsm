<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="ShopWWI">
<meta name="copyright" content="ShopWWI Inc. All Rights Reserved">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?><?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body {
_behavior: url(<?php echo MEMBER_TEMPLATES_URL;
?>/css/csshover.htc);
}
</style>
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo MEMBER_TEMPLATES_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo MEMBER_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo MEMBER_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
$(function(){
	//首页左侧分类菜单
	$(".category ul.menu").find("li").each(
		function() {
			$(this).hover(
				function() {
				    var cat_id = $(this).attr("cat_id");
					var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
					menu.show();
					$(this).addClass("hover");					
					var menu_height = menu.height();
					if (menu_height < 60) menu.height(80);
					menu_height = menu.height();
					var li_top = $(this).position().top;
					$(menu).css("top",-li_top + 50);
				},
				function() {
					$(this).removeClass("hover");
				    var cat_id = $(this).attr("cat_id");
					$(this).find("div[cat_menu_id='"+cat_id+"']").hide();
				}
			);
		}
	);
	$(".mod_minicart").hover(function() {
		$("#nofollow,#minicart_list").addClass("on");
	},
	function() {
		$("#nofollow,#minicart_list").removeClass("on");
	});
	$('.mod_minicart').mouseover(function(){// 运行加载购物车
		load_cart_information();
		$(this).unbind('mouseover');
	});
    <?php if (C('fullindexer.open')) { ?>
	// input ajax tips
	$('#keyword').focus(function(){
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('').removeClass('tips');
		}
	}).blur(function(){
		if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
			$(this).addClass('tips').val($(this).attr('title'));
		}
	}).blur().autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                 $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#top_search_form').submit();
		}
	});
	<?php } ?>

	$('#button').click(function(){
	    if ($('#keyword').val() == '') {
		    if ($('#keyword').attr('data-value') == '') {
			    return false
			} else {
				window.location.href="<?php echo SHOP_SITE_URL?>/index.php?act=search&op=index&keyword="+$('#keyword').attr('data-value');
			    return false;
			}
	    }
	});
	$(".head-search-bar").hover(null,
	function() {
		$('#search-tip').hide();
	});
	// input ajax tips
	$('#keyword').focus(function(){$('#search-tip').show()}).autocomplete({
		//minLength:0,
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                    $('#search-tip').hide();
                    $(".head-search-bar").unbind('mouseover');
                    $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#top_search_form').submit();
		}
	});
	$('#search-his-del').on('click',function(){$.cookie('<?php echo C('cookie_pre')?>his_sh',null,{path:'/'});$('#search-his-list').empty();});
});
$(function(){ 
	var act = "<?php echo $_GET['act']?>";
	if (act == "store_list"){
		$("#head-search-bar").children('#hdSearchTab').children('a:eq(1)').addClass("d");
		$("#head-search-bar").children('#hdSearchTab').children('a:eq(0)').removeClass("d");
		}
	$("#head-search-bar").children('#hdSearchTab').children('a').click(function(){
		$(this).parent().children('a').removeClass("d");
		$(this).addClass("d");
		$('#search_act').attr("value",$(this).attr("act"));
		$('#keyword').attr("placeholder",$(this).attr("title"));
	});
	$("#keyword").blur();
	$('#search-tip').hide();
	
});
</script>
</head>
<body>
<!-- PublicTopLayout Begin -->
<?php require_once template('layout/layout_top');?>
<!-- PublicHeadLayout Begin -->
<style>
.search-form select{display:none;}
.search-form .select_box{font-size:12px;color:#999999;width:70px; background:#f8f8f8;;line-height:38px;float:left;position:relative;}
.search-form .select_showbox{height:36px;background:url(images/search_ico.png) no-repeat 80px center;text-indent:1.5em;}
.search-form .select_showbox.active{background:url(images/search_ico_hover.png) no-repeat 80px center;}
.search-form .select_option{border:2px solid #f55;border-top:none;display:none;left:-2px;top:36px;position:absolute;z-index:99;background:#fff;}
.search-form .select_option li{text-indent:1.5em;width:70px;cursor:pointer;}
.search-form .select_option li.selected{background-color:#F3F3F3;color:#999;}
.search-form .select_option li.hover{background:#BEBEBE;color:#fff;}

.search-form input.inp_srh,.search input.btn_srh{border:none;background:none;height:35px;line-height:35px;float:left}
.search-form input.inp_srh{outline:none;width:365px;}
.search-form input.btn_srh{background:#f58400;color:#FFF;font-family:"微软雅黑";font-size:15px;width:60px;}</style>
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
    <div class="logo-test"><?php echo $output['setting_config']['shopwwi_stitle']; ?></div>
   
    <div class="head-search-layout">
      <div class="head-search-bar" id="head-search-bar">
 <div class="hd_serach_tab" id="hdSearchTab">
      <a href="javascript:;" act="search" title="请输入您要搜索的商品关键字" class="d">商品</a>
      <a href="javascript:;"  act="store_list" title="请输入您要搜索的店铺关键字">店铺</a>
<i></i>
</div>     		
        <form action="<?php echo SHOP_SITE_URL;?>" method="get" class="search-form" id="top_search_form">
            <input name="act" id="search_act" value="search" type="hidden">
          <?php
			if ($_GET['keyword']) {
				$keyword = stripslashes($_GET['keyword']);
			} elseif ($output['rec_search_list']) {
                $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];
				$keyword_name = $_stmp['name'];
				$keyword_value = $_stmp['value'];
			} else {
                $keyword = '';
            }
		?>
          <input name="keyword" id="keyword" type="text" class="input-text" value="<?php echo $keyword;?>" maxlength="60" x-webkit-speech lang="zh-CN" onwebkitspeechchange="foo()" placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的商品关键字';?>" data-value="<?php echo rawurlencode($keyword_value);?>" x-webkit-grammar="builtin:search" autocomplete="off" />
          <input type="submit" id="button" value="<?php echo $lang['nc_common_search'];?>" class="input-submit">
        </form>
        <div class="search-tip" id="search-tip">
          <div class="search-history">
            <div class="title">历史纪录<a href="javascript:void(0);" id="search-his-del">清除</a></div>
            <ul id="search-his-list">
              <?php if (is_array($output['his_search_list']) && !empty($output['his_search_list'])) { ?>
              <?php foreach($output['his_search_list'] as $v) { ?>
              <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $v));?>"><?php echo $v ?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
          <div class="search-hot">
            <div class="title">热门搜索...</div>
            <ul>
              <?php if (is_array($output['rec_search_list']) && !empty($output['rec_search_list'])) { ?>
              <?php foreach($output['rec_search_list'] as $v) { ?>
              <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $v['value']));?>"><?php echo $v['value']?></a></li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="keyword">
        <ul>
          <?php if(is_array($output['hot_search']) && !empty($output['hot_search'])) { foreach($output['hot_search'] as $val) { ?>
          <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $val));?>"><?php echo $val; ?></a></li>
          <?php } }?>
        </ul>
      </div>
    </div>
    <div class="mod_minicart" style="">
		<a id="nofollow" target="_self" href="<?php echo SHOP_SITE_URL;?>/index.php?act=cart" class="mini_cart_btn">
                        <i class="cart_icon"></i> 
			<em class="cart_num"><?php echo $output['cart_goods_num'];?></em>
			<span>购物车</span>
		</a>
		<div id="minicart_list" class="minicart_list">
			<div class="spacer"></div>
			<div class="list_detail">
				<!--购物车有商品时begin-->
				<ul><img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /></ul> 
				<div class="checkout_box">
                    <p class="fl">共<em class="tNum"><?php echo $output['cart_goods_num'];?></em>件商品,合计：<em class="tSum">0</em></p>
					<a rel="nofollow" class="checkout_btn" href="<?php echo SHOP_SITE_URL;?>/index.php?act=cart" target="_self"> 去结算 </a>
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
        <?php if ($output['cart_goods_num'] > 0) { ?>
        <div class="addcart-goods-num"><?php echo $output['cart_goods_num'];?></div>
        <?php } ?>
        <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4>最新加入的商品</h4>
          </div>
          <div class="incart-goods-box">
            <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif" /> </div>
          </div>
          <div class="checkout"> <span class="total-price">共<i><?php echo $output['cart_goods_num'];?></i><?php echo $lang['nc_kindof_goods'];?></span><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=cart" class="btn-cart">结算购物车中的商品</a> </div>
        </dd>
      </dl>
    </div>-->
  </header>
</div>
<!-- PublicHeadLayout End --> 

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout <?php if($output['channel']) {echo 'channel-'.$output['channel']['channel_style'].' channel-'.$output['channel']['channel_id'];} ?>">
  <div class="wrapper">
    <div class="all-category">
      <?php require template('layout/home_goods_class');?>
    </div>
    <ul class="site-menu">
      <li><a href="<?php echo SHOP_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><span><?php echo $lang['nc_index'];?></span></a></li>
      <?php if (C('groupbuy_allow')){ ?>
      <li class="navitems-on"><a href="<?php echo urlShop('show_groupbuy', 'index');?>" <?php if($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <span><?php echo $lang['nc_groupbuy'];?></span></a></li>
      <?php } ?>
      <li><a href="<?php echo urlShop('brand', 'index');?>" <?php if($output['index_sign'] == 'brand' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <span><?php echo $lang['nc_brand'];?></span></a></li>
            <li><a href="<?php echo urlShop('promotion','index');?>" <?php if($output['index_sign'] == 'promotion' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <span>疯抢</span></a></li>
      <?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
      <li><a href="<?php echo urlShop('pointshop', 'index');?>" <?php if($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <span><?php echo $lang['nc_pointprod'];?></span></a></li>
      <?php } ?>
      <?php if (C('cms_isuse')){ ?>
      <li><a href="<?php echo urlShop('special', 'special_list');?>" <?php if($output['index_sign'] == 'special' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <span>专题</span></a></li>
      <?php } ?>
    </ul>
  </div>
</nav>
