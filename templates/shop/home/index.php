<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL; ?>/js/home_index.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/waypoints.js"></script>
<div class="clear"></div><!-- HomeFocusLayout Begin-->
<div class="home-focus-layout">
    <?php echo $output['web_html']['index_pic']; ?>
    <div class="wwi-right-slide">
        <div class="item clearfix">
            <a class="safe" href="<?php echo urlShop('show_joinin', 'index'); ?>" target="_blank"><i></i><span>招商入驻</span></a><a class="free"
                                                                                     href="<?php echo urlShop('shangjia_login', 'show_login'); ?>"
                                                                                     target="_blank"><i></i><span>商家管理</span></a><a
                class="speed" href="<?php echo DELIVERY_SITE_URL; ?>" target="_blank"><i></i><span>物流自提</span></a></div>
        <div class="right-sidebar">
            <div class="box-all">
                <div class="title"><i></i><em>公告</em><a name="index2_none_gg_gd" target="_blank" mpf="sqde"
                                                        href="<?php echo urllogin('article', 'article', array('ac_id' => 1)); ?>"
                                                        class="more">更多</a></div>
                <div class="notice">
                    <div class="bg"></div>
                    <ul class="list"><?php if (!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list']))
                        {
                            $i = 0; ?><?php foreach ($output['show_article']['notice']['list'] as $val)
                        {
                            $i ++; ?>
                            <li <?php if ($i == 1) echo 'class="hot"' ?>><a target="_blank"
                                                                            href="<?php echo empty($val['article_url']) ? urlMember('article', 'show', array('article_id' => $val['article_id'])) : $val['article_url']; ?>"
                                                                            title="<?php echo $val['article_title']; ?>"><i>【公告】</i><em><?php echo str_cut($val['article_title'], 24); ?></em>
                            </a></li><?php }
                        } ?></ul>
                </div>
            </div>
        </div>
    </div>
</div><!--HomeFocusLayout End--><!--网店运维切换栏组合 stat-->
<div class="wrapper">
    <div class="home-sale-suiji"><?php echo $output['web_html']['index_sale']; ?></div>
    <!--网店运维切换栏组合 end-->
    <div class="mt10"><?php echo loadadv(38, 'html'); ?> </div>
    <!--StandardLayout Begin--> <?php echo $output['web_html']['index']; ?> <!--StandardLayout End-->
    <div class="mt10"><?php echo loadadv(9, 'html'); ?></div>
</div>
<div class="wwi-main-footr">
    <div class="wrapper">
        <div class="sale_lum clearfix">
            <div class="m" id="sale_cx">
                <div class="mt">
                    <div class="title-line"></div>
                    <h2><span>特卖TeMai</span></h2></div>
                <div class="sale_cx"><?php if (!empty($output['group_list']) && is_array($output['group_list']))
                    { ?>
                        <div class="groupbuy">
                        <ul><?php foreach ($output['group_list'] as $val)
                            { ?>
                                <li>
                                <dl style=" background-image:url(<?php echo gthumb($val['groupbuy_image1'], 'small'); ?>)">
                                    <dt><?php echo $val['groupbuy_name']; ?></dt>
                                    <dd class="price"><span
                                            class="groupbuy-price"><?php echo ncPriceFormatForList($val['groupbuy_price']); ?></span><span
                                            class="buy-button"><a
                                                href="<?php echo urlShop('show_groupbuy', 'groupbuy_detail', array('group_id' => $val['groupbuy_id'])); ?>">立即抢</a></span>
                                    </dd>
                                    <dd class="time"><span
                                            class="sell">已售<em><?php echo $val['buy_quantity'] + $val['virtual_quantity']; ?></em></span>
                                        <span class="time-remain"
                                              count_down="<?php echo $val['end_time'] - TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian']; ?>
                                            <em time_id="h">0</em><?php echo $lang['text_hour']; ?> <em
                                                time_id="m">0</em><?php echo $lang['text_minute']; ?><em
                                                time_id="s">0</em><?php echo $lang['text_second']; ?> </span></dd>
                                </dl></li><?php } ?></ul></div><?php } ?></div>
            </div>
            <div class="m" id="sale_xs">
                <div class="mt">
                    <div class="title-line"></div>
                    <h2><span>疯抢FengQiang</span></h2></div>
                <div class="sale_xs">
                    <div class="home-sale-layout">
                        <div
                            class="left-sidebar"><?php if (!empty($output['special_list']) && is_array($output['special_list']))
                            { ?><?php foreach ($output['special_list'] as $value)
                            { ?><a href="<?php echo $value['special_link']; ?>"
                                   title="<?php echo $value['special_title']; ?>" target="_blank"><img width="275"
                                                                                                       mpf="sqde"
                                                                                                       title="<?php echo $value['special_title']; ?>"
                                                                                                       height="135"
                                                                                                       shopwwi-url="<?php echo getCMSSpecialImageUrl($value['special_image']); ?>"
                                                                                                       rel='lazy'
                                                                                                       src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif"
                                                                                                       class=""
                                                                                                       alt="<?php echo $value['special_title']; ?>">
                                </a><?php }
                            } ?></div><?php if (!empty($output['xianshi_item']) && is_array($output['xianshi_item']))
                        { ?>
                            <div class="right-sidebar">
                            <div id="saleDiscount" class="sale-discount">
                                <ul><?php foreach ($output['xianshi_item'] as $val)
                                    { ?>
                                        <li>
                                        <dl>
                                            <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
                                            <dd class="goods-thumb"><a
                                                    href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>">
                                                    <img shopwwi-url="<?php echo thumb($val, 240); ?>" rel='lazy'
                                                         src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif"></a></dd>
                                            <dd class="goods-price"><?php echo ncPriceFormatForList($val['xianshi_price']); ?>
                                                <span
                                                    class="original"><?php echo ncPriceFormatForList($val['goods_price']); ?></span>
                                            </dd>
                                            <dd class="goods-price-discount">
                                                <em><?php echo $val['xianshi_discount']; ?></em></dd>
                                            <dd class="time-remain"
                                                count_down="<?php echo $val['end_time'] - TIMESTAMP; ?>"><i></i><em
                                                    time_id="d">0</em><?php echo $lang['text_tian']; ?><em
                                                    time_id="h">0</em><?php echo $lang['text_hour']; ?> <em time_id="m">0</em><?php echo $lang['text_minute']; ?>
                                                <em time_id="s">0</em><?php echo $lang['text_second']; ?> </dd>
                                            <dd class="goods-buy-btn"></dd>
                                        </dl></li><?php } ?></ul>
                            </div></div><?php } ?></div>
                </div>
            </div>
            <div class="m" id="share">
                <div class="mt">
                    <div class="title-line"></div>
                    <h2><span>晒单ShaiDan</span></h2></div>
                <div class="share" id="sl">
                    <ul class="show_share"><?php if (!empty($output['goods_evaluate_info']) && is_array($output['goods_evaluate_info']))
                        { ?><?php foreach ($output['goods_evaluate_info'] as $k => $v)
                        { ?>
                            <li>
                            <div class="p-img"><a
                                    href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['geval_goodsid'])); ?>"
                                    target="_blank"><img
                                        src="<?php echo strpos($v['goods_pic'], 'http') === 0 ? $v['goods_pic'] : UPLOAD_SITE_URL . "/" . ATTACH_GOODS . "/" . $v['geval_storeid'] . "/" . $v['geval_goodsimage']; ?>"
                                        alt="<?php echo $v['geval_goodsname']; ?>" width="100" height="100"></a></div>
                            <div class="p-info">
                                <div class="author-info"><img
                                        title="<?php echo str_cut($v['geval_frommembername'], 2) . '***'; ?>"
                                        shopwwi-url="<?php echo getMemberAvatarForID($v['geval_frommemberid']); ?>"
                                        rel='lazy' src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif"
                                        alt="<?php echo str_cut($v['geval_frommembername'], 2) . '***'; ?>" width="28"
                                        height="28"><span><?php echo str_cut($v['geval_frommembername'], 2) . '***'; ?></span>
                                </div>
                                <div class="p-detail"><a target="_blank" title="<?php echo $v['geval_content']; ?>"
                                                         href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['geval_goodsid'])); ?>"><?php echo $v['geval_content']; ?>
                                        <span class="icon-r">”</span></a><span class="icon-l">“</span></div>
                            </div></li><?php }
                        } ?></ul>
                    <script type="text/javascript">$(document).ready(function () {
                            function statusRunner() {
                                setTimeout(function () {
                                    var sl = $('#sl li'), f = $('#sl li:last');
                                    f.hide().insertBefore(sl.eq(0)).css('opacity', '0.1');
                                    f.slideDown(500, function () {
                                        f.animate({opacity: 1});
                                    });
                                    statusRunner();
                                }, 7000);
                            }

                            statusRunner();
                        });
                        $(".home-standard-layout .left-sidebar .title a ").hover(function () {
                            $(".home-standard-layout .tabs-nav").addClass("wwi-hover");
                        });
                        $(".home-standard-layout .tabs-nav .close").click(function () {
                            $(".home-standard-layout .tabs-nav").removeClass("wwi-hover");
                        });    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="nav_box">
    <ul><?php if (is_array($output['lc_list']) && !empty($output['lc_list']))
        {
            $i = 0 ?><?php foreach ($output['lc_list'] as $v)
        {
            $i ++ ?>
            <li class="nav_Sd_<?php echo $i; ?> <?php if ($i == 1) echo 'hover' ?>"><a class="num"
                                                                                       href="javascript:;"><?php echo $v['value'] ?></a>
            <a class="word" href="javascript:;"><?php echo $v['name'] ?></a></li><?php }
        } ?></ul>
</div></div>