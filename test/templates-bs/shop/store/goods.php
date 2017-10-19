<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<!--<link href="--><?php //echo RESOURCE_SITE_URL; ?><!--/pc/css/home_goods.min.css" rel="stylesheet" type="text/css">-->
<!--商品详情页static-->
<script type="text/javascript" src="<?php echo STATIC_URL; ?>/js/cloud-zoom.min.js" charset="utf-8"></script>
<div class="container">
    <div class="row">
    <div class="col-md-4">  Z
            <!-- 焦点图 -->
            <div class="good-zoom">
                <img class="cloudzoom"
                     alt="Cloud Zoom small image"
                     id="zoom1"
                     src="<?php echo $output["goods_image"]["0"]["1"] ?>"
                     title="鼠标滚轮向上或向下滚动，能放大或缩小图片哦~"
                     data-cloudzoom="
                     zoomImage:'<?php echo $output["goods_image"]["0"]["2"] ?>',
                     zoomSizeMode: &quot;image&quot;,
                     tintColor:&quot;#000&quot;,
                     tintOpacity:0.25,
                     captionPosition:&quot;bottom&quot;,
                     maxMagnification:4,
                     autoInside:750
                     ">
            </div>
            <div class="zoom-ctl">
                <?php foreach ($output["goods_image"] as $key => $value){ ?>
                <a href="<?php echo $value['2'] ?>" class="thumb-link">
                    <img class="cloudzoom-gallery" width="60" height="60"
                         src="<?php echo $value['0'] ?>"
                         title="鼠标滚轮向上或向下滚动，能放大或缩小图片哦~" alt="Cloud Zoom thumb image"
                         data-cloudzoom="
                         useZoom:'#zoom1',
                         image:'<?php echo $value['1'] ?>',
                         zoomImage:'<?php echo $value['2'] ?>'">
                </a>
                <?php } ?>
            </div>
            <!-- 焦点图 E-->
            <!-- E 商品图片及收藏分享 -->

            <div class="good-handle">
                <!-- S 分享 -->
                <a href="javascript:void(0);" class="btn btn-default" id="sharegood"
                   data-param='{"gid":"<?php echo $output['goods']['goods_id']; ?>"}'><i></i><?php echo $lang['goods_index_snsshare_goods']; ?>
                    <span>(<em><?php echo intval($output['goods']['sharenum']) > 0 ? intval($output['goods']['sharenum']) : 0; ?></em>)</span></a>
                <!-- S 收藏 -->
                <a href="javascript:collect_goods('<?php echo $output['goods']['goods_id']; ?>','count','goods_collect');"
                   class="btn btn-default"><?php echo $lang['goods_index_favorite_goods']; ?><span>(<em><?php echo $output['goods']['goods_collect'] ?></em>)</span></a>
                <!-- S 举报 -->
                <?php if ($output['inform_switch'])
                { ?>
                    <a href="<?php if ($_SESSION['is_login']){ ?>index.php?act=member_inform&op=inform_submit&goods_id=<?php echo $output['goods']['goods_id']; ?><?php }
                    else{ ?>javascript:login_dialog();<?php } ?>" title="<?php echo $lang['goods_index_goods_inform']; ?>"
                       class="btn btn-default"><?php echo $lang['goods_index_goods_inform']; ?></a>
                <?php } ?>
                <!-- End --> </div>
        </div>
        <div class="col-md-8">
            <!-- S 商品基本信息 -->
            <div class="good-summary">
                <div class="page-header">
                    <h1><?php echo $output['goods']['goods_name']; ?></h1>
                    <?php if($output['goods']['goods_jingle']){?>
                        <strong><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']); ?></strong>
                    <?php } ?>
                    <div class="goods-flag">
                        <div class="fl">
                            <img class="jImg" src="http://img04.bubugao.com/15181617d6b_65_075d7b658d31de1146bec900351683aa_42x42.png!s1">
                        </div>
                        <ul class="fl ml10">
                            <li class="area-en" title=""><?php echo $output['goods']['brand_info']['country_en']; ?></li>
                            <li class="area-zh" title=""><?php echo $output['goods']['brand_info']['country_zh']; ?></li>
                        </ul>
                    </div>
                </div>

                <div class="good-meta">
                    <!-- S 商品参考价格 -->
                    <dl>
                        <dt><?php echo $lang['goods_index_goods_cost_price']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd class="p-del">
                            <strong><?php echo $lang['currency'] . ncPriceFormat($output['goods']['goods_marketprice']); ?></strong>
                        </dd>
                    </dl>
                    <!-- E 商品参考价格 -->
                    <!-- S 商品发布价格 -->
                    <dl>
                        <dt><?php echo $lang['goods_index_goods_price']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd class="p-normal">
                            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price']))
                            { ?>
                                <strong><i><?php echo $lang['currency']; ?></i><?php echo ncPriceFormat($output['goods']['promotion_price']); ?>
                                </strong>
                                <em>(原售价<?php echo $lang['nc_colon']; ?><?php echo $lang['currency'] . ncPriceFormat($output['goods']['goods_price']); ?>
                                    )</em>
                            <?php }
                            else
                            { ?>
                                <strong><?php echo $lang['currency'] . ncPriceFormat($output['goods']['goods_price']); ?></strong>
                            <?php } ?>
                        </dd>
                    </dl>
                    <!-- E 商品发布价格 -->
                    <dl>
                        <dt>商品评分：</dt>
                        <!-- S 描述相符评分 -->
                        <dd><span class="raty"
                                  data-score="<?php echo $output['goods_evaluate_info']['star_average']; ?>"></span><a
                                href="#goodsRate">共有<?php echo $output['goods']['evaluation_count']; ?>条评价</a></dd>
                        <!-- E 描述相符评分 -->
                    </dl>
                    <div class="good-qrcode">
                        <img src="<?php echo goodsQRCode($output['goods']); ?>" title="用商城手机客户端扫描二维码直达商品详情内容">
                        <p>客户端扫购有惊喜</p>
                    </div>
                </div>

                <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_verify'] == 1){ ?>
                    <!-- S 促销 -->
                    <?php if (isset($output['goods']['promotion_type']) ){ ?>
                    <div class="good-promo">
                        <dl>
                            <dt>促销：</dt>
                            <dd class="promotion-info">
                                <?php if (isset($output['goods']['title']) && $output['goods']['title'] != ''){ ?>
                                    <span class="sale-name"><?php echo $output['goods']['title']; ?></span>
                                <?php } ?>
                                <!-- S 限时折扣 -->
                                <?php if ($output['goods']['promotion_type'] == 'xianshi'){ ?>
                                    <span class="sale-rule w400">直降<em><?php echo $lang['currency'] . ncPriceFormat($output['goods']['down_price']); ?></em>
                                        <?php if ($output['goods']['lower_limit']){ ?>
                                            <?php echo sprintf('最低%s件起，', $output['goods']['lower_limit']); ?><?php echo $output['goods']['explain']; ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>
                                <!-- E 限时折扣  -->
                                <!-- S 特卖-->
                                <?php if ($output['goods']['promotion_type'] == 'groupbuy'){ ?>
                                    <?php if ($output['goods']['upper_limit']){ ?>
                                    <em><?php echo sprintf('最多限购%s件', $output['goods']['upper_limit']); ?></em>
                                <?php } ?>
                                    <span><?php echo $output['goods']['remark']; ?></span><br>
                                <?php } ?>
                                <!-- E 特卖 -->
                            </dd>
                        </dl>
                    </div>
                    <?php } ?>
                    <!-- E 促销 -->

                    <!-- S 物流与运费-->
                    <?php if ($output['goods']['is_virtual'] == 0 && $output['goods']['goods_storage'] > 0){ ?>
                        <div class="good-shipping">
                            <dl>
                                <dt>配送至：</dt>
                                <dd>
                                    <div id="freight_selector" class="freight-select hover">
                                        <div class="text">
                                            <?php if ($output['store_info']['deliver_region'] == '|'){echo '请选择地区';} else{
                                                echo $output['store_info']['deliver_region'] ? str_replace(' ', '', $output['store_info']['deliver_region'][1]) : '请选择地区';
                                            } ?>
                                        </div>
                                        <div class="content">
                                            <div id="freight_area" class="freight-area" data-widget="tabs">
                                                <div class="mt">
                                                    <ul class="nav nav-tabs" role="tablist">
                                                        <li data-index="0" data-widget="tab-item" class="curr">
                                                            <a href="#none" class="hover">
                                                                <em><?php echo $output['store_info']['deliver_region_names'][0] ? $output['store_info']['deliver_region_names'][0] : '请选择' ?></em>
                                                                <b class="caret"></b>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div data-widget="tab-content" data-area="0">
                                                    <ul class="area-list">
                                                    </ul>
                                                </div>
                                                <div data-widget="tab-content" data-area="1"
                                                     style="display: none;">
                                                    <ul class="area-list">
                                                    </ul>
                                                </div>
                                                <div data-widget="tab-content" data-area="2"
                                                     style="display: none;">
                                                    <ul class="area-list">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="close" aria-label="关闭" onclick="$('#freight_selector').removeClass('hover')"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div id="freight_prompt" class="freight-prompt">
                                        <strong><?php echo $output['goods']['goods_storage'] > 0 ? '有货' : '无货' ?></strong>
                                        <?php if (!$output['goods']['transport_id'])
                                        { ?>
                                            <?php echo $output['goods']['goods_freight'] > 0 ? '运费：' . ncPriceFormat($output['goods']['goods_freight']) . ' 元' : '免运费' ?>
                                        <?php } ?>
                                    </div>
                                </dd>
                            </dl>
                            <!-- S 门店自提 -->
                            <?php if ($output['goods']['is_chain']){ ?>
                                <hr/>
                                <dl class="ncs-chain">
                                    <dt>门店服务：</dt>
                                    <dd><i class="icon-chain"></i><a href="javascript:void(0);" nctype="show_chain"
                                                                     data-goodsid="<?php echo $output['goods']['goods_id']; ?>">门店自提</a>·
                                        选择有现货的门店下单，可立即提货
                                    </dd>
                                </dl>
                            <?php } ?>
                            <!-- E 门店自提 -->
                        </div>
                    <?php } ?>
                    <!-- S 物流与运费  -->

                    <!-- S 虚拟商品-->
                    <?php if ($output['goods']['is_virtual'] == 1){ ?>
                        <dl>
                            <dt>提货方式：</dt>
                            <dd>
                                <ul>
                                    <li class="sp-txt"><a href="javascript:void(0)" class="hovered">电子兑换券<i></i></a></li>
                                </ul>
                            </dd>
                        </dl>
                        <!-- 虚拟商品有效期 -->
                        <dl>
                            <dt>有&nbsp;效&nbsp;期：</dt>
                            <dd>即日起 到 <?php echo date('Y-m-d H:i:s', $output['goods']['virtual_indate']); ?></dd>
                        </dl>
                    <?php }?>
                    <!-- S 虚拟商品-->

                    <!-- S 商品规格值-->
                    <?php if (is_array($output['goods']['spec_name'])){ ?>
                    <div class="good-spec">
                        <hr/>
                        <?php foreach ($output['goods']['spec_name'] as $key => $val){ ?>
                        <dl nctype="nc-spec">
                            <dt><?php echo $val; ?><?php echo $lang['nc_colon']; ?></dt>
                            <dd>
                                <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])){ ?>
                                    <ul nctyle="ul_sign">
                                        <?php foreach ($output['goods']['spec_value'][$key] as $k => $v){ ?>
                                            <li>
                                                <a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])){echo 'active';} ?>" data-param="{valid:<?php echo $k; ?>}"><?php echo $v; ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </dd>
                        </dl>
                    <?php } ?>
                    </div>
                    <?php } ?>
                    <!-- E 商品规格值-->

                    <!-- S 购买按钮 -->
                    <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] > 0){ ?>
                    <div class="good-buy mt10">
                        <div class="form-group good-qty-input">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" yf_id="decrease"><b class="glyphicon glyphicon-minus"></b></a>
                                </div>
                                <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="form-control input-text">
                                <div class="input-group-addon">
                                    <a href="javascript:void(0)" yf_id="increase"><b class="glyphicon glyphicon-plus"></b></a>
                                </div>
                            </div>
                        </div>
                        <div class="good-buy-btn">
                            <?php if ($output['goods']['buynow'] == true){ ?>
                                <!-- 立即购买-->
                                <a role="button" class="btn btn-lg btn-warning" href="javascript:void(0);" yf_id="buynow_submit" title="<?php echo $output['goods']['buynow_text']; ?>">
                                    <?php echo $output['goods']['buynow_text']; ?>
                                </a>
                            <?php } ?>
                            <?php if ($output['goods']['cart'] == true){ ?>
                                <!-- 加入购物车-->
                                <a role="button" class="btn btn-lg btn-danger" href="javascript:void(0);" yf_id="addcart_submit" title="<?php echo $lang['goods_index_add_to_cart']; ?>">
                                    <i class="fa fa-shopping-cart"></i><?php echo $lang['goods_index_add_to_cart']; ?>
                                </a>
                            <?php } ?>
                            <!-- S 加入购物车弹出提示框 -->
                            <div class="modal good-cart-popup">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="$('.good-cart-popup').css({'display':'none'});"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title"><?php echo $lang['goods_index_cart_success']; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <?php echo $lang['goods_index_cart_have']; ?> <strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods']; ?> <?php echo $lang['goods_index_total_price']; ?><?php echo $lang['nc_colon']; ?><em id="bold_mly" class="saleP"></em>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="javascript:void(0);" class="btn btn-small btn-default" onClick="location.href='<?php echo SHOP_SITE_URL . DS ?>index.php?act=cart'"><?php echo $lang['goods_index_view_cart']; ?></a>
                                            <a href="javascript:void(0);" class="btn btn-small btn-default" value="" onClick="$('.good-cart-popup').css({'display':'none'});"><?php echo $lang['goods_index_continue_shopping']; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- E 加入购物车弹出提示框 -->
                        </div>
                    </div>
                    <?php } ?>
                    <!-- E 购买按钮 -->
                    <!-- S 提示已选规格及库存不足无法购买 -->
                    <?php if ($output['goods']['goods_storage'] <= 0){ ?>
                        <div class="alert alert-warning alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <a role="button" class="btn btn-info" href="javascript:void(0);" nctype="arrival_notice"  title="库存不足,申请到货通知">库存不足,申请到货通知</a>
                        </div>
                    <?php } ?>
                    <!-- E 提示已选规格及库存不足无法购买 -->

                <?php } ?>
            </div>
            <!-- E 商品基本信息 -->
    </div>
</div>

<div id="content" class="row mt20">
    <div class="col-md-9 col-md-push-3" id="good_main">
        <ul class="nav nav-tabs">
            <li class="active">
                <a id="tab_good_intro" href="#good_intro"><?php echo $lang['goods_index_goods_info']; ?></a>
            </li>
            <li>
                <a id="tab_good_rate" href="#good_rate"><?php echo $lang['goods_index_evaluation']; ?>
                    <span class="badge"><?php echo $output['goods_evaluate_info']['all']; ?></span>
                </a>
            </li>
        </ul>
        <div class="good-intro">
            <div class="content bd" id="good_intro">
                <?php if (is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name']))
                { ?>
                    <ul class="good-intro-sort">
                        <li>商家货号：<?php echo $output['goods']['goods_serial']; ?></li>
                        <?php if (isset($output['goods']['brand_name']))
                        {
                            echo '<li>' . $lang['goods_index_brand'] . $lang['nc_colon'] . $output['goods']['brand_name'] . '</li>';
                        } ?>
                        <?php if (is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr']))
                        { ?>
                            <?php foreach ($output['goods']['goods_attr'] as $val)
                        {
                            $val = array_values($val);
                            echo '<li>' . $val[0] . $lang['nc_colon'] . $val[1] . '</li>';
                        } ?>
                        <?php } ?>
                        <?php if (is_array($output['goods']['goods_custom']))
                        {
                            foreach ($output['goods']['goods_custom'] as $val)
                            {
                                if ($val['value'] != '')
                                {
                                    echo '<li>' . $val['name'] . $lang['nc_colon'] . $val['value'] . '</li>';
                                }
                            }
                        } ?>
                    </ul>
                <?php } ?>
                <div class="good-info-content">
                    <?php if (isset($output['plate_top'])){ ?>
                        <div class="top-template"><?php echo $output['plate_top']['plate_content'] ?></div>
                    <?php } ?>
                    <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
                    <?php if (isset($output['plate_bottom'])){ ?>
                        <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content'] ?></div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="good-comment">
            <div class="good-title-bar">
                <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation']; ?></a></h4>
            </div>
            <div class="good-info-content bd" id="good_rate">
                <div class="top">
                    <div class="rate">
                        <p><strong><?php echo $output['goods_evaluate_info']['good_percent']; ?></strong><sub>%</sub>好评
                        </p>
                        <span>共有<?php echo $output['goods_evaluate_info']['all']; ?>人参与评分</span></div>
                    <div class="percent">
                        <dl>
                            <dt>好评<em>(<?php echo $output['goods_evaluate_info']['good_percent']; ?>%)</em></dt>
                            <dd><i style="width: <?php echo $output['goods_evaluate_info']['good_percent']; ?>%"></i>
                            </dd>
                        </dl>
                        <dl>
                            <dt>中评<em>(<?php echo $output['goods_evaluate_info']['normal_percent']; ?>%)</em></dt>
                            <dd><i style="width: <?php echo $output['goods_evaluate_info']['normal_percent']; ?>%"></i>
                            </dd>
                        </dl>
                        <dl>
                            <dt>差评<em>(<?php echo $output['goods_evaluate_info']['bad_percent']; ?>%)</em></dt>
                            <dd><i style="width: <?php echo $output['goods_evaluate_info']['bad_percent']; ?>%"></i>
                            </dd>
                        </dl>
                    </div>
                    <div class="btns"><span>您可对已购商品进行评价</span>

                        <p><a href="<?php if ($output['goods']['is_virtual'])
                            {
                                echo urlShop('member_vr_order', 'index');
                            }
                            else
                            {
                                echo urlShop('member_order', 'index');
                            } ?>" class="btn btn-grapefruit" target="_blank">评价商品</a></p>
                    </div>
                </div>
                <div class="good-title-nav">
                    <ul id="comment_tab">
                        <li data-type="all" class="current"><a
                                href="javascript:void(0);"><?php echo $lang['goods_index_evaluation']; ?>
                                (<?php echo $output['goods_evaluate_info']['all']; ?>)</a></li>
                        <li data-type="1"><a
                                href="javascript:void(0);">好评(<?php echo $output['goods_evaluate_info']['good']; ?>)</a>
                        </li>
                        <li data-type="2"><a
                                href="javascript:void(0);">中评(<?php echo $output['goods_evaluate_info']['normal']; ?>
                                )</a></li>
                        <li data-type="3"><a
                                href="javascript:void(0);">差评(<?php echo $output['goods_evaluate_info']['bad']; ?>)</a>
                        </li>
                    </ul>
                </div>
                <!-- 商品评价内容部分 -->
                <div id="good_evaluate" class="good-commend-main"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-md-pull-9">
        <?php include template1('store.left_mall_related'); ?>
        <?php if ($output['viewed_goods']){ ?>
        <!-- 最近浏览 -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>最近浏览</h4>
            </div>
            <div class="panel-body">
                <ul class="viewed-g">
                    <?php if (!empty($output['viewed_goods']) && is_array($output['viewed_goods'])){ ?>
                        <?php foreach ($output['viewed_goods'] as $k => $v){ ?>
                        <li>
                            <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id'])); ?>"
                               target="_blank">
                                <img src="<?php echo thumb($v, 60); ?>" title="<?php echo $v['goods_name']; ?>"
                                     alt="<?php echo $v['goods_name']; ?>">
                            </a>
                            <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id'])); ?>"
                               target="_blank"><?php echo $v['goods_name']; ?></a>

                            <div
                                class="g-price"><?php echo $lang['currency']; ?><?php echo ncPriceFormat($v['goods_promotion_price']); ?></div>
                        </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
            <a role="button" class="btn btn-primary btn-lg btn-block" href="<?php echo SHOP_SITE_URL; ?>/index.php?controller=member_goodsbrowse&action=list">全部浏览历史</a>
        </div>
    <?php } ?>
</div>
</div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL; ?>/index.php">
    <input id="act" name="act" type="hidden" value="buy"/>
    <input id="op" name="op" type="hidden" value="buy_step1"/>
    <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<!--<script src="--><?php //echo RESOURCE_SITE_URL; ?><!--/js/sns.js" type="text/javascript" charset="utf-8"></script>-->
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/custom.min.js"
        charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css"
      id="cssfile2"/>
<script type="text/javascript">
    /** 辅助浏览 **/

        //产品图片
    jQuery(function ($) {
        // 放大镜效果 产品图片
        CloudZoom.quickStart();

        // 图片切换效果
        $(".good-gallery-ctrl li").first().addClass('current');
        $('.good-gallery-ctrl').find('li').mouseover(function () {
            $(this).first().addClass("current").siblings().removeClass("current");
        });
    });

    //收藏分享处下拉操作
    //jQuery.divselect = function (divselectid, inputselectid) {
    //    var inputselect = $(inputselectid);
    //    $(divselectid).mouseover(function () {
    //        var ul = $(divselectid + " ul");
    //        ul.slideDown("fast");
    //        if (ul.css("display") == "none") {
    //            ul.slideDown("fast");
    //        }
    //    });
    //    $(divselectid).live('mouseleave', function () {
    //        $(divselectid + " ul").hide();
    //    });
    //};
    $(function () {
        <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
        // 加入购物车
        $('a[yf_id="addcart_submit"]').click(function () {
            //alert('加入到购物车');
            if (typeof(allow_buy) != 'undefined' && allow_buy === false) return;
            addcart(<?php echo $output['goods']['goods_id'];?>, checkQuantity(), 'addcart_callback');
        });
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        // 立即购买
        $('a[yf_id="buynow_submit"]').click(function () {
            if (typeof(allow_buy) != 'undefined' && allow_buy === false) return;
            buynow(<?php echo $output['goods']['goods_id']?>, checkQuantity());
        });
        <?php }?>
        <?php }?>
        // 到货通知
        <?php if ($output['goods']['goods_storage'] == 0 || $output['goods']['goods_state'] == 0) {?>
        $('a[nctype="arrival_notice"]').click(function () {
            <?php if ($_SESSION['is_login'] !== '1'){?>
            login_dialog();
            <?php }else{?>
            ajax_form('arrival_notice', '到货通知', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id']));?>', 350);
            <?php }?>
        });
        <?php }?>

        //// 分享收藏下拉操作
        //$.divselect("#handle-l");
        //$.divselect("#handle-r");

        // 规格选择
        $('dl[nctype="nc-spec"]').find('a').each(function () {
            $(this).click(function () {
                if ($(this).hasClass('hovered')) {
                    return false;
                }
                $(this).parents('ul:first').find('a').removeClass('hovered');
                $(this).addClass('hovered');
                checkSpec();
            });
        });

    });

    function checkSpec() {
        var spec_param = <?php echo $output['spec_list'];?>;
        var spec = new Array();
        $('ul[nctyle="ul_sign"]').find('.hovered').each(function () {
            var data_str = '';
            eval('data_str =' + $(this).attr('data-param'));
            spec.push(data_str.valid);
        });
        spec1 = spec.sort(function (a, b) {
            return a - b;
        });
        var spec_sign = spec1.join('|');
        $.each(spec_param, function (i, n) {
            if (n.sign == spec_sign) {
                window.location.href = n.url;
            }
        });
    }

    // 验证购买数量
    function checkQuantity() {
        var quantity = parseInt($("#quantity").val());
        if (quantity < 1) {
            alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
            $("#quantity").val('1');
            return false;
        }
        max = <?php echo $output['goods']['goods_storage']; ?>;
        <?php if (!empty($output['goods']['upper_limit'])) {?>
        max = <?php echo $output['goods']['upper_limit'];?>;
        if (quantity > max) {alert('最多限购' + max + '件');return false;}
        <?php } ?>
        if (quantity > max) {
            alert("<?php echo $lang['goods_index_add_too_much'];?>");
            return false;
        }
        return quantity;
    }

    // 立即购买js
    function buynow(goods_id, quantity, chain_id, area_id, area_name, area_id_2) {
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        if (!quantity) {
            return;
        }
        <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
        alert('不能购买自己店铺的商品');
        return;
        <?php } ?>
        $("#cart_id").val(goods_id + '|' + quantity);
        if (typeof chain_id == 'number') {
            $('#buynow_form').append('<input type="hidden" name="ifchain" value="1"><input type="hidden" name="chain_id" value="' + chain_id + '"><input type="hidden" name="area_id" value="' + area_id + '"><input type="hidden" name="area_name" value="' + area_name + '"><input type="hidden" name="area_id_2" value="' + area_id_2 + '">');
        }
        $("#buynow_form").submit();
        <?php }?>
    }

    $(function () {
        //选择地区查看运费
        $('#transport_pannel>a').click(function () {
            var id = $(this).attr('nctype');
            if (id == 'undefined') return false;
            var _self = this, tpl_id = '<?php echo $output['goods']['transport_id'];?>';
            var url = 'index.php?act=goods&op=calc&rand=' + Math.random();
            $('#transport_price').css('display', 'none');
            $('#loading_price').css('display', '');
            $.getJSON(url, {'id': id, 'tid': tpl_id}, function (data) {
                if (data == null) return false;
                if (data != 'undefined') {
                    $('#nc_kd').html('运费<?php echo $lang['nc_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');
                } else {
                    '<?php echo $lang['goods_index_trans_for_seller'];?>';
                }
                $('#transport_price').css('display', '');
                $('#loading_price').css('display', 'none');
                $('#ncrecive').html($(_self).html());
            });
        });
        /** goods.php **/

        // 商品详情默认情况下显示全部
        $('#tab_good_intro').click(function () {
            $('.bd').css('display', '');
            $('.hd').css('display', '');
        });
        // 点击评价隐藏其他以及其标题栏
        $('#tab_good_rate').click(function () {
            $('.bd').css('display', 'none');
            $('#good_rate').css('display', '');
            $('.hd').css('display', 'none');
        });
        //信用评价动态评分打分人次Tab切换
        $(".ncs-rate-tab > li > a").mouseover(function (e) {
            if (e.target == this) {
                var tabs = $(this).parent().parent().children("li");
                var panels = $(this).parent().parent().parent().children(".ncs-rate-panel");
                var index = $.inArray(this, $(this).parent().parent().find("a"));
                if (panels.eq(index)[0]) {
                    tabs.removeClass("current ").eq(index).addClass("current ");
                    panels.addClass("hide").eq(index).removeClass("hide");
                }
            }
        });

//触及显示缩略图
        $('.goods-pic > .thumb').hover(
            function () {
                $(this).next().css('display', 'block');
            },
            function () {
                $(this).next().css('display', 'none');
            }
        );

        /* 商品购买数量增减js */
        // 增加
        $('a[yf_id="increase"]').click(function () {
            num = parseInt($('#quantity').val());
            <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
            max = <?php echo $output['goods']['virtual_limit'];?>;
            if (num >= max) {
                alert('最多限购' + max + '件');
                return false;
            }
            <?php } ?>
            <?php if (!empty($output['goods']['upper_limit'])) {?>
            max = <?php echo $output['goods']['upper_limit'];?>;
            if (num >= max) {
                alert('最多限购' + max + '件');
                return false;
            }
            <?php } ?>
            max = <?php echo $output['goods']['goods_storage']; ?>;
            if (num < max) {
                $('#quantity').val(num + 1);
            }
        });
        //减少
        $('a[yf_id="decrease"]').click(function () {
            num = parseInt($('#quantity').val());
            if (num > 1) {
                $('#quantity').val(num - 1);
            }
        });

        //评价列表
        $('#comment_tab').on('click', 'li', function () {
            $('#comment_tab li').removeClass('current');
            $(this).addClass('current');
            loadGoodEvaluate($(this).attr('data-type'));
        });
        loadGoodEvaluate('all');
        function loadGoodEvaluate(type) {
            var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id']));?>';
            url += '&type=' + type;
            $("#good_evaluate").load(url, function () {
                $(this).find('[nctype="mcard"]').membershipCard({type: 'shop'});
            });
        }

        //记录浏览历史
        $.get("index.php?act=goods&op=addbrowse", {gid:<?php echo $output['goods']['goods_id'];?>});

        $('[nctype="show_chain"]').click(function () {
            _goods_id = $(this).attr('data-goodsid');
            ajax_form('show_chain', '查看门店', 'index.php?act=goods&op=show_chain&goods_id=' + _goods_id, 640);
        });

    });

    /* 加入购物车后的效果函数 */
    function addcart_callback(data) {
        $('#bold_num').html(data.num);
        $('#bold_mly').html(price_format(data.amount));
        $('.good-cart-popup').fadeIn('fast');
    }

    <?php if($output['goods']['goods_state'] == 1 && $output['goods']['goods_verify'] == 1 && $output['goods']['is_virtual'] == 0){ ?>
    var $cur_area_list, $cur_tab, next_tab_id = 0, cur_select_area = [], calc_area_id = '', calced_area = [], cur_select_area_ids = [];
    $(document).ready(function () {
        $("#freight_selector").hover(function () {
            //如果店铺没有设置默认显示区域，马上异步请求
            <?php if (!$output['store_info']['deliver_region']) { ?>
            if (typeof nc_a === "undefined") {
                $.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function (data) {
                    nc_a = data;
                    $cur_tab = $('#freight_area').find('li[data-index="0"]');
                    _loadArea(0);
                });
            }
            <?php } ?>
            $(this).addClass("hover");
            $(this).on('mouseleave', function () {
                $(this).removeClass("hover");
            });
        });

        $('ul[class="area-list"]').on('click', 'a', function () {
            $('#freight_selector').unbind('mouseleave');
            var tab_id = parseInt($(this).parents('div[data-widget="tab-content"]:first').attr('data-area'));
            if (tab_id == 0) {
                cur_select_area = [];
                cur_select_area_ids = []
            }
            ;
            if (tab_id == 1 && cur_select_area.length > 1) {
                cur_select_area.pop();
                cur_select_area_ids.pop();
                if (cur_select_area.length > 1) {
                    cur_select_area.pop();
                    cur_select_area_ids.pop();
                }
            }
            next_tab_id = tab_id + 1;
            var area_id = $(this).attr('data-value');
            $cur_tab = $('#freight_area').find('li[data-index="' + tab_id + '"]');
            $cur_tab.find('em').html($(this).html());
            $cur_tab.find('i').html(' ∨');
            if (tab_id < 2) {
                calc_area_id = area_id;
                cur_select_area.push($(this).html());
                cur_select_area_ids.push(area_id);
                $cur_tab.find('a').removeClass('hover');
                $cur_tab.nextAll().remove();
                if (typeof nc_a === "undefined") {
                    $.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function (data) {
                        nc_a = data;
                        _loadArea(area_id);
                    });
                } else {
                    _loadArea(area_id);
                }
            } else {
                //点击第三级，不需要显示子分类
                calc_area_id = area_id;
                if (cur_select_area.length == 3) {
                    cur_select_area.pop();
                    cur_select_area_ids.pop();
                }
                cur_select_area.push($(this).html());
                cur_select_area_ids.push(area_id);
                $('#freight_selector > div[class="text"] > div').html(cur_select_area.join(''));
                $('#freight_selector').removeClass("hover");
                _calc();
            }
            $('#freight_area').find('li[data-widget="tab-item"]').on('click', 'a', function () {
                var tab_id = parseInt($(this).parent().attr('data-index'));
                if (tab_id < 2) {
                    $(this).parent().nextAll().remove();
                    $(this).addClass('hover');
                    $('#freight_area').find('div[data-widget="tab-content"]').each(function () {
                        if ($(this).attr("data-area") == tab_id) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
        });
        function _loadArea(area_id) {
            if (nc_a[area_id] && nc_a[area_id].length > 0) {
                $('#freight_area').find('div[data-widget="tab-content"]').each(function () {
                    if ($(this).attr("data-area") == next_tab_id) {
                        $(this).show();
                        $cur_area_list = $(this).find('ul');
                        $cur_area_list.html('');
                    } else {
                        $(this).hide();
                    }
                });
                var areas = [];
                areas = nc_a[area_id];
                for (i = 0; i < areas.length; i++) {
                    $cur_area_list.append("<li><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
                }
                if (area_id > 0) {
                    $cur_tab.after('<li data-index="' + (next_tab_id) + '" data-widget="tab-item"><a class="hover" href="#none" ><em>请选择</em><b class="caret"></b></a></li>');
                }
            } else {
                //点击第一二级时，已经到了最后一级
                $cur_tab.find('a').addClass('hover');
                $('#freight_selector > div[class="text"] > div').html(cur_select_area);
                $('#freight_selector').removeClass("hover");
                _calc();
            }
        }

        //计算运费，是否配送
        function _calc() {
            $.cookie('dregion', cur_select_area_ids.join(' ') + '|' + cur_select_area.join(' '), {expires: 30});
            <?php if (! $output['goods']['transport_id']) { ?>
            return;
            <?php } ?>
            var _args = '';
            _args += "&tid=<?php echo $output['goods']['transport_id']?>";
            <?php if ($output['store_info']['is_own_shop']) { ?>
            _args += "&super=1";
            <?php } ?>
            if (_args != '') {
                _args += '&area_id=' + calc_area_id;
                if (typeof calced_area[calc_area_id] == 'undefined') {
                    //需要请求配送区域设置
                    $.getJSON(SITEURL + "/index.php?act=goods&op=calc&" + _args + "&myf=<?php echo $output['store_info']['store_free_price']?>&callback=?", function (data) {
                        allow_buy = data.total ? true : false;
                        calced_area[calc_area_id] = data.total;
                        if (data.total === false) {
                            $('#freight_prompt > strong').html('无货').next().remove();
                            $('a[nctype="buynow_submit"]').addClass('no-buynow');
                            $('a[nctype="addcart_submit"]').addClass('no-buynow');
                        } else {
                            $('#freight_prompt > strong').html('有货 ').next().remove();
                            $('#freight_prompt > strong').after('<span>' + data.total + '</span>');
                            $('a[nctype="buynow_submit"]').removeClass('no-buynow');
                            $('a[nctype="addcart_submit"]').removeClass('no-buynow');
                        }
                    });
                } else {
                    if (calced_area[calc_area_id] === false) {
                        $('#freight_prompt > strong').html('无货').next().remove();
                        $('a[nctype="buynow_submit"]').addClass('no-buynow');
                        $('a[nctype="addcart_submit"]').addClass('no-buynow');
                    } else {
                        $('#freight_prompt > strong').html('有货 ').next().remove();
                        $('#freight_prompt > strong').after('<span>' + calced_area[calc_area_id] + '</span>');
                        $('a[nctype="buynow_submit"]').removeClass('no-buynow');
                        $('a[nctype="addcart_submit"]').removeClass('no-buynow');
                    }
                }
            }
        }

        //如果店铺设置默认显示配送区域
        <?php if ($output['store_info']['deliver_region']) { ?>
        if (typeof nc_a === "undefined") {
            $.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function (data) {
                nc_a = data;
                $cur_tab = $('#freight_area').find('li[data-index="0"]');
                _loadArea(0);
                $('ul[class="area-list"]').find('a[data-value="<?php echo $output['store_info']['deliver_region_ids'][0]?>"]').click();
                <?php if ($output['store_info']['deliver_region_ids'][1]) { ?>
                $('ul[class="area-list"]').find('a[data-value="<?php echo $output['store_info']['deliver_region_ids'][1]?>"]').click();
                <?php } ?>
                <?php if ($output['store_info']['deliver_region_ids'][2]) { ?>
                $('ul[class="area-list"]').find('a[data-value="<?php echo $output['store_info']['deliver_region_ids'][2]?>"]').click();
                <?php } ?>
            });
        }
        <?php } ?>
    });
    <?php }?>
</script> 
