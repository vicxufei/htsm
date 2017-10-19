<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_group.css" rel="stylesheet" type="text/css">
<style type="text/css">
.nch-breadcrumb-layout {display: none; }
</style>
<div class="ncg-container clearfix">
  <div class="ncg-category" id="ncgCategory">
    <h3>实物特卖</h3>
    <ul>
<?php $i = 0; $names = $output['groupbuy_classes']['name']; foreach ((array) $output['groupbuy_classes']['children'][0] as $v) { if (++$i > 6) break; ?>
      <li><a href="<?php echo urlShop('show_groupbuy', 'groupbuy_list', array('class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
    </ul>
    <h3>虚拟特卖</h3>
    <ul>
<?php $i = 0; $names = $output['groupbuy_vr_classes']['name']; foreach ((array) $output['groupbuy_vr_classes']['children'][0] as $v) { if (++$i > 6) break; ?>
      <li><a href="<?php echo urlShop('show_groupbuy', 'vr_groupbuy_list', array('vr_class' => $v)); ?>"><?php echo $names[$v]; ?></a></li>
<?php } ?>
    </ul>
  </div>
    <?php if (!empty($output['picArr'])) { ?>
    <div class="ncg-slides-banner">
      <ul id="fullScreenSlides" class="full-screen-slides">
        <?php foreach($output['picArr'] as $p) { ?>
        <li style=" background: url(<?php echo UPLOAD_SITE_URL.'/'.ATTACH_LIVE.'/'.$p[0];?>) 50% 0% no-repeat <?php echo $p[1];?>;"><a href="<?php echo $p[2];?>" target="_blank"></a></li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
  <div class="ncg-content">


    <div class="group-list mt20">
       
        <div class="wwi-tm">
        <div class="item"><div class="scopes"><a href="<?php echo urlShop('show_groupbuy', 'groupbuy_list'); ?>"/><img src="img/swtm.jpg" /></a></div></div>
         <?php if (!empty($output['groupbuy'])) { ?>
                <?php foreach ($output['groupbuy'] as $groupbuy) { ?>
<div class="item"><div class="scope">
    <dl class="goods"><dt class="goods-thumb"> <a title="<?php echo $groupbuy['groupbuy_name'];?>" target="_blank" href="<?php echo $groupbuy['groupbuy_url'];?>"><img src="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>" /></a> </dt>
      <dd class="goods-name"><span><strong>限时特卖</strong></span> <a target="_blank" href="<?php echo $groupbuy['groupbuy_url'];?>"><?php echo $groupbuy['groupbuy_name'];?></a></dd></dl>
<div class="goods-time"><!-- 倒计时 距离本期结束 --><i class="icon-time"></i>剩余时间：<span class="time-remain" count_down="<?php echo $groupbuy['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </span></div>
<div class="goods-buy"><a href="<?php echo $groupbuy['groupbuy_url'];?>" class="btn">立即抢购</a> <span class="sale">特卖价<em><?php echo ncPriceFormat($groupbuy['groupbuy_price']);?></em>元</span> <span class="depreciate"><i class="icon-long-arrow-down"></i>直降：¥<?php echo sprintf("%01.2f",$groupbuy['goods_price']-$groupbuy['groupbuy_price']);?></span> </div>
  </div>
</div>
<?php } ?> <?php } else { ?>
        <div class="item"><div class="scope">暂无实物特卖推荐哦！</div></div><?php } ?>
        <!--虚拟特卖开始-->
            <div class="item"><div class="scopes"><a href="<?php echo urlShop('show_groupbuy', 'vr_groupbuy_list'); ?>"/><img src="img/xntm.jpg" /></a></div></div>   
                      <?php if (!empty($output['vr_groupbuy'])) { ?>
               <?php foreach($output['vr_groupbuy'] as $groupbuy) { ?>
<div class="item"><div class="scope">
    <dl class="goods"><dt class="goods-thumb"> <a title="<?php echo $groupbuy['groupbuy_name'];?>" target="_blank" href="<?php echo $groupbuy['groupbuy_url'];?>"><img src="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>" /></a> </dt>
      <dd class="goods-name"><span><strong>限时特卖</strong></span> <a target="_blank" href="<?php echo $groupbuy['groupbuy_url'];?>"><?php echo $groupbuy['groupbuy_name'];?></a></dd></dl>
<div class="goods-time"><!-- 倒计时 距离本期结束 --><i class="icon-time"></i>剩余时间：<span class="time-remain" count_down="<?php echo $groupbuy['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </span></div>
<div class="goods-buy"><a href="<?php echo $groupbuy['groupbuy_url'];?>" class="btn">立即抢购</a> <span class="sale">特卖价<em><?php echo ncPriceFormat($groupbuy['groupbuy_price']);?></em>元</span> <span class="depreciate"><i class="icon-long-arrow-down"></i>直降：¥<?php echo sprintf("%01.2f",$groupbuy['goods_price']-$groupbuy['groupbuy_price']);?></span> </div>
  </div>
</div>
<?php } ?> <?php } else { ?>
        <div class="item"><div class="scope">暂无虚拟特卖推荐哦！</div></div><?php } ?> 
        
</div>

    </div>
  </div>
</div>
