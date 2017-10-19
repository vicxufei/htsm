<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<script language="javascript">
function fade() {
	$("img[rel='lazy']").each(function () {
		var $scroTop = $(this).offset();
		if ($scroTop.top <= $(window).scrollTop() + $(window).height()) {
			$(this).hide();
			$(this).attr("src", $(this).attr("shopwwi-url"));
			$(this).removeAttr("rel");
			$(this).removeAttr("name");
			$(this).fadeIn(500);
		}
	});
}

if($("img[rel='lazy']").length > 0) {
	$(window).scroll(function () {
		fade();
	});
};
fade();
</script><?php echo getChat($layout);?><div class="wwi-footer"><div id="cti" class="wrapper"><ul><?php if ($output['contract_list']) {?><?php foreach($output['contract_list'] as $k=>$v){?><?php if($v['cti_descurl']){ ?><li><a href="<?php echo $v['cti_descurl'];?>" target="_blank"><span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span></a></li><?php }else{ ?><li> <span class="icon"> <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" /> </span> <span class="name"> <?php echo $v['cti_name']; ?> </span> </li><?php }}}?><li class="rf">客服电话：<em class="red"><?php echo $output['setting_config']['shopwwi_phone']; ?></em>  <em class="rgb9"><?php echo $output['setting_config']['shopwwi_time']; ?></em></li></ul></div><div id="faq"><div class="wrapper"><?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?><ul><?php foreach ($output['article_list'] as $k=> $article_class){ ?><?php if(!empty($article_class)){ ?><li><dl class="s<?php echo ''.$k+1;?>"><dt><?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?></dt><?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?><?php foreach ($article_class['list'] as $article){ ?><dd><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlMember('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a></dd><?php }}?></dl></li><?php }}?><li class="cooperation"><dl><dt>商家合作</dt><dd><p>错过天猫？别再错过我们！</p><p>太划算商城开启全新旅程！</p><a href="<?php echo urlShop('show_joinin', 'index');?>" class="btn" target="_blank"><span>关于入驻</span>&gt;</a></dd></dl></li></ul><?php }?></div></div><div id="footer"><div class="wrapper"><div class="screen clearfix"><div class="fl right-flag"><a href="http://www.shopwwi.com" target="_blank" rel="nofollow"><img src="<?php echo SHOP_SITE_URL;?>/img/credit-flag3.png"></a><a href="http://www.htths.com/" target="_blank" rel="nofollow"><img src="<?php echo SHOP_SITE_URL;?>/img/isc2.png"></a></div><div class="fl about-us"><p><a href="<?php echo SHOP_SITE_URL;?>">返回首页</a><?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?><?php foreach($output['nav_list'] as $nav){?><?php if($nav['nav_location'] == '2'){?><span>|</span> <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){case '0':echo $nav['nav_url'];break; case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break; case '2':echo urlMember('article', 'article',array('ac_id'=>$nav['item_id']));break; case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;}?>"><?php echo $nav['nav_title'];?></a><?php }}}?><span>|</span><a href="<?php echo urlshop('link');?>">友情链接</a></p><p>CopyRight © 2014-2016 太仓和团商贸有限公司 <a href="http://www.miibeian.gov.cn/" target="_blank" mpf="sqde" style="color:#666"><?php echo $output['setting_config']['icp_number']; ?></a> 版权所有 客户服务中心:QQ313026006 </p><p><?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?></p></div></div>
<?php if (C('debug') == 1){?><div id="think_page_trace" class="trace"><fieldset id="querybox"><legend><?php echo $lang['nc_debug_trace_title'];?></legend><div> <?php print_r(Tpl::showTrace());?> </div></fieldset></div><?php }?></div><script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script><link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script> <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script><link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css"><!-- 对比 --> <script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/compare.js"></script> <script type="text/javascript">
$(function(){
// Membership card
$('[nctype="mcard"]').membershipCard({type:'shop'});
});
</script>