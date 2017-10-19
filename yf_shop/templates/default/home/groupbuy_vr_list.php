<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<?php require('groupbuy_head.php');?>
<div class="ncg-container">
    <?php if (!empty($output['picArr'])) { ?>
    <div class="ncg-slides-banner">
      <ul id="fullScreenSlides" class="full-screen-slides">
        <?php foreach($output['picArr'] as $p) { ?>
        <li style=" background: url(<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.'/'.$p[0];?>) 50% 0% no-repeat <?php echo $p[1];?>;"><a href="<?php echo $p[2];?>" target="_blank"></a></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <?php if (!empty($output['groupbuys'])) { ?>    
 <div class="explosion g-floor">
<div class="black-bg"></div>
<div class="wrapper explosion-content">
<div class="overseas-title">
<h4>即将开始</h4>
<span class="eng-title">Ranking</span>
<div class="line"></div>
</div>
<div class="commodity-list">
<div class="introduce">
<div class="text">
<p>每日精选超优惠的商品</p>
<p>供您选择</p>
<p>从没有过的价格</p>
<p>款款让您心动</p>
</div>
<div class="time">
<p>每日</p>
<p><i>上新</i></p>
</div>
</div>
<ul id="todayPro">
 <?php foreach ($output['groupbuys'] as $groupbuys) { ?>
<li><div class="commodity-intro">    <a target="_blank" href="<?php echo $groupbuys['goods_url'];?>" title="<?php echo $groupbuys['groupbuy_name'];?>" class="commodity-link"><img shopwwi-url="<?php echo gthumb($groupbuys['groupbuy_image'],'mid');?>" rel='lazy' src="<?php echo SHOP_SITE_URL;?>/img/loading.gif" alt="" width="160" height="160" class="transform"><span class="readyStart"></span></a>    <p class="commodity-name">        <span class="country-logo-small -small"></span>        <a target="_blank" href="<?php echo $groupbuys['goods_url'];?>" title="<?php echo $groupbuys['groupbuy_name'];?>"><?php echo $groupbuys['groupbuy_name'];?></a>    </p>    <div class="discount">        <i class="front">5</i><i class="behind">.1</i><i class="text">折</i>    </div></div><div class="commodity-detail">    <p class="time-left j-time"> <span><i class="icon-time"></i><b>距特卖开始：</b><span class="time-remain" count_down="<?php echo $groupbuys['start_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><i><?php echo $lang['text_tian'];?></i><em time_id="h">0</em><i>:</i><em time_id="m">0</em><i>:</i><em time_id="s">0</em> </span>    </p><?php list($integer_part, $decimal_part) = explode('.', ncPriceFormat($groupbuys['groupbuy_price']));?><span class="price"><i class="price-logo">¥</i><em><?php echo $integer_part;?></em><i class="price-last">.<?php echo $decimal_part;?></i></span>    <p class="consult-price">市场参考价：<span><i class="price-logo">¥</i><em><?php echo ncPriceFormat($groupbuys['goods_price']);?></em></span></p>    <a name="hwg_sg427_bktj_jrgwcan01" href="#" class="join-cart" partnumber="129823137" vendor="0070088130" grppurid="814024">即将开始</a></div></li><?php } ?>

</ul>
</div>
</div>
</div>   <?php } ?>

  <div class="ncg-content">
    <?php if (!empty($output['groupbuy'])) { ?>
    <div class="overseas-title"><h4>本期推荐</h4><span class="eng-title">Remced Arrival</span></div>
  <div class="theNew-content clearfix">
   <?php foreach ($output['groupbuy'] as $groupbuy) { ?>
<div class="theNewitem item-hover">      <div class="commodity-introduce">          <span class="country-logo-big USA-big"></span></div>      <img class="commodity-img" shopwwi-url="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>" rel='lazy' src="<?php echo SHOP_SITE_URL;?>/img/loading.gif" alt="<?php echo $groupbuy['groupbuy_name'];?>" width="260" height="260" style="opacity: 1;">      <div class="commodity-detail">          <p class="commodity-name"><?php echo $groupbuy['groupbuy_name'];?></p>          <p class="commodity-text"><?php echo $groupbuy['remark'];?></p>          <p class="time-left j-time">              <span class="logo"></span>              <span class="text"><!-- 倒计时 距离本期结束 --><i class="icon-time"></i><b>距特卖结束：</b><span class="time-remain" count_down="<?php echo $groupbuy['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><i><?php echo $lang['text_tian'];?></i><em time_id="h">0</em><i>:</i><em time_id="m">0</em><i>:</i><em time_id="s">0</em></span></p>          <div class="price-wrapper">              <span class="discount"><?php list($integers_part, $decimals_part) = explode('.', ncPriceFormat($groupbuy['groupbuy_rebate']));?> <i class="front"><?php echo $integers_part;?></i><i class="behind">.<?php echo $decimals_part;?></i><i class="text">折</i>              </span><?php list($integer_part, $decimal_part) = explode('.', ncPriceFormat($groupbuy['groupbuy_price']));?><span class="price"><i class="price-logo">¥</i><em><?php echo $integer_part;?></em><i class="price-last">.<?php echo $decimal_part;?></i></span>               <p class="consult-price">市场参考价：<span><i class="price-logo">¥</i><em><?php echo ncPriceFormat($groupbuy['goods_price']);?></em></span></p>          </div>          <p class="buy-number"><i><?php echo $groupbuy['buy_quantity']+$groupbuy['virtual_quantity'];?></i>人已抢购！</p>      </div><div></div>      <a target="_blank" href="<?php echo $groupbuy['goods_url'];?>" class="overseas-itemlink"></a>      <a href="<?php echo $groupbuy['goods_url'];?>" class="join-cart" >立即抢购</a>  </div><?php } ?>

</div><?php } ?>
    <div class="ncg-screen">

<?php if ($output['groupbuy_vr_classes']['children'][0]) { ?>
      <!-- 分类过滤列表 -->
      <dl>
        <dt>分类：</dt>
        <dd class="nobg<?php if (!($hasChildren = !empty($_GET['vr_class']))) echo ' selected'; ?>"><a href="<?php echo dropParam(array('vr_class', 'vr_s_class')); ?>"><?php echo $lang['text_no_limit']; ?></a></dd>
<?php $names = $output['groupbuy_vr_classes']['name']; foreach ($output['groupbuy_vr_classes']['children'][0] as $v) { ?>
        <dd<?php if ($hasChildren && $_GET['vr_class'] == $v) echo ' class="selected"'; ?>><a href="<?php echo replaceAndDropParam(array('vr_class' => $v), array('vr_s_class')); ?>"><?php echo $names[$v]; ?></a></dd>
<?php } ?>
<?php if ($hasChildren && $output['groupbuy_vr_classes']['children'][$_GET['vr_class']]) { ?>
        <ul>
<?php foreach ($output['groupbuy_vr_classes']['children'][$_GET['vr_class']] as $v) { ?>
          <li<?php if ($_GET['vr_s_class'] == $v) echo ' class="selected"'; ?>><a href="<?php echo replaceParam(array('vr_s_class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
        </ul>
<?php } ?>
      </dl>
<?php } ?>

<?php
$vr_city_id = (int) $output['vr_city_id'];
$vr_area_id = (int) $output['vr_area_id'];
$vr_mall_id = (int) $output['vr_mall_id'];

if ($vr_city_id > 0) {
    $names = $output['groupbuy_vr_cities']['name'];

    $areaIds = (array) $output['groupbuy_vr_cities']['children'][$vr_city_id];
    $areaIsNoLimit = !$areaIds || $vr_area_id < 1;
    $mallIds = in_array($vr_area_id, $areaIds)
        ? (array) $output['groupbuy_vr_cities']['children'][$vr_area_id]
        : array();

?>
      <!-- 区域过滤列表 -->
      <dl>
        <dt>区域：</dt>
        <dd class="nobg<?php if ($areaIsNoLimit) echo ' selected'; ?>"><a href="<?php echo dropParam(array('vr_area', 'vr_mall')); ?>"><?php echo $lang['text_no_limit']; ?></a></dd>
<?php foreach ($areaIds as $v) { ?>
        <dd<?php if ($vr_area_id == $v) echo ' class="selected"'; ?>><a href="<?php echo replaceAndDropParam(array('vr_area' => $v), array('vr_mall')); ?>"><?php echo $names[$v]; ?></a></dd>
<?php } ?>
<?php if ($mallIds) { ?>
        <ul>
<?php foreach ($mallIds as $v) { ?>
          <li<?php if ($vr_mall_id == $v) echo ' class="selected"'; ?>><a href="<?php echo replaceParam(array('vr_mall' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
        </ul>
<?php } ?>
      </dl>
<?php } ?>

      <!-- 价格过滤列表 -->
      <dl>
        <dt><?php echo $lang['text_price'];?>：</dt>
        <dd class="<?php echo empty($_GET['groupbuy_price'])?'selected':''?>"><a href="<?php echo dropParam(array('groupbuy_price'));?>"><?php echo $lang['text_no_limit'];?></a></dd>
        <?php if(is_array($output['price_list'])) { ?>
        <?php foreach($output['price_list'] as $groupbuy_price) { ?>
        <dd <?php echo $_GET['groupbuy_price'] == $groupbuy_price['range_id']?"class='selected'":'';?>> <a href="<?php echo replaceParam(array('groupbuy_price' => $groupbuy_price['range_id']));?>"><?php echo $groupbuy_price['range_name'];?></a> </dd>
        <?php } ?>
        <?php } ?>
      </dl>

      <dl class="ncg-sortord">
        <dt>排序：</dt>
        <dd class="<?php echo empty($_GET['groupbuy_order_key'])?'selected':''?>"><a href="<?php echo dropParam(array('groupbuy_order_key', 'groupbuy_order'))?>"><?php echo $lang['text_default'];?><i></i></a></dd>
        <dd <?php echo $_GET['groupbuy_order_key'] == '1'?"class='selected'":'';?>><a <?php echo $_GET['groupbuy_order_key'] == '1'?"class='". ($_GET['groupbuy_order'] == 1 ? 'asc' : 'desc') ."'":'';?> href="<?php echo ($_GET['groupbuy_order_key'] == '1' && $_GET['groupbuy_order'] == '2' ? replaceParam(array('groupbuy_order_key' => '1', 'groupbuy_order' => '1')) : replaceParam(array('groupbuy_order_key' => '1', 'groupbuy_order' => '2')));?>"><?php echo $lang['text_price'];?><i></i></a></dd>
        <dd <?php echo $_GET['groupbuy_order_key'] == '2'?"class='selected'":'';?>><a <?php echo $_GET['groupbuy_order_key'] == '2'?"class='". ($_GET['groupbuy_order'] == 1 ? 'asc' : 'desc') ."'":'';?> href="<?php echo ($_GET['groupbuy_order_key'] == '2' && $_GET['groupbuy_order'] == '2' ? replaceParam(array('groupbuy_order_key' => '2', 'groupbuy_order' => '1')) : replaceParam(array('groupbuy_order_key' => '2', 'groupbuy_order' => '2')));?>"><?php echo $lang['text_rebate'];?><i></i></a></dd>
        <dd <?php echo $_GET['groupbuy_order_key'] == '3'?"class='selected'":'';?>><a <?php echo $_GET['groupbuy_order_key'] == '3'?"class='". ($_GET['groupbuy_order'] == 1 ? 'asc' : 'desc') ."'":'';?> href="<?php echo ($_GET['groupbuy_order_key'] == '3' && $_GET['groupbuy_order'] == '2' ? replaceParam(array('groupbuy_order_key' => '3', 'groupbuy_order' => '1')) : replaceParam(array('groupbuy_order_key' => '3', 'groupbuy_order' => '2')));?>"><?php echo $lang['text_sale'];?><i></i></a></dd>
      </dl>
    </div>
<div class="wwi-tm  clearfix">
    <?php if (!empty($output['groupbuy_list']) && is_array($output['groupbuy_list'])) { ?>
    <!-- 特卖活动列表 -->

        <?php foreach ($output['groupbuy_list'] as $groupbuy) { ?>
<div class="item"><div class="scope">
    <dl class="goods"><dt class="goods-thumb"> <a title="<?php echo $groupbuy['groupbuy_name'];?>" target="_blank" href="<?php echo $groupbuy['groupbuy_url'];?>"><img shopwwi-url="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>" rel='lazy' src="<?php echo SHOP_SITE_URL;?>/img/loading.gif" /></a> </dt>
      <dd class="goods-name"><span><strong>限时特卖</strong></span> <a target="_blank" href="<?php echo $groupbuy['groupbuy_url'];?>"><?php echo $groupbuy['groupbuy_name'];?></a></dd></dl>
<div class="goods-time"><!-- 倒计时 距离本期结束 --><i class="icon-time"></i>剩余时间：<span class="time-remain" count_down="<?php echo $groupbuy['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </span></div>
<div class="goods-buy"><a href="<?php echo $groupbuy['groupbuy_url'];?>" class="btn">立即抢购</a> <span class="sale">特卖价<em><?php echo ncPriceFormat($groupbuy['groupbuy_price']);?></em>元</span> <span class="depreciate"><i class="icon-long-arrow-down"></i>直降：¥<?php echo sprintf("%01.2f",$groupbuy['goods_price']-$groupbuy['groupbuy_price']);?></span> </div>
  </div>
</div>
<?php } ?> <?php } else { ?>
        <div class="item"><div class="scope">暂无虚拟特卖推荐哦！</div></div><?php } ?> 

  </div>
</div>
