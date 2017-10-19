<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div class="squares"><?php if (!empty($output['goods_list']) && is_array($output['goods_list']))
    { ?>
        <ul class="list_pic"><?php foreach ($output['goods_list'] as $value)
    { ?>
        <li class="item">
        <div class="goods-content">
            <div class="goods-pic">
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $value['goods_id'])); ?>"
                   target="_blank" title="<?php echo $value['goods_name']; ?>"><img
                        shopwwi-url="<?php echo cthumb($value['goods_image'], 240, $value['store_id']); ?>" rel='lazy'
                        src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif" title="<?php echo $value['goods_name']; ?>"
                        alt="<?php echo $value['goods_name']; ?>"/></a>
            </div><?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1)
            { ?>
                <div class="goods-promotion"><span>团购商品</span></div><?php }
            elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)
            { ?>
                <div class="goods-promotion"><span>限时折扣</span></div><?php } ?>
            <div class="goods-info">
                <div class="goods-name"><a
                        href="<?php echo urlShop('goods', 'index', array('goods_id' => $value['goods_id'])); ?>"
                        target="_blank"
                        title="<?php echo $value['goods_jingle']; ?>"><?php echo $value['goods_name_highlight']; ?>
                        <em><?php echo $value['goods_jingle']; ?></em></a></div>
                <div class="goods-price"><em class="sale-price"
                                             title="<?php echo $lang['goods_class_index_store_goods_price'] . $lang['nc_colon'] . $lang['currency'] . ncPriceFormat($value['goods_promotion_price']); ?>"><?php echo ncPriceFormatForList($value['goods_promotion_price']); ?></em>
                    <em class="market-price"
                        title="市场价：<?php echo $lang['currency'] . $value['goods_marketprice']; ?>"><?php echo ncPriceFormatForList($value['goods_marketprice']); ?></em><?php if ($value["contractlist"])
                    { ?>
                    <div class="goods-cti"> <?php foreach ($value["contractlist"] as $gcitem_k => $gcitem_v)
                        { ?><span <?php if ($gcitem_v['cti_descurl'])
                        { ?>onclick="window.open('<?php echo $gcitem_v['cti_descurl']; ?>');"
                            style="cursor: pointer;"<?php } ?> title="<?php echo $gcitem_v['cti_name']; ?>"> <img
                                shopwwi-url="<?php echo $gcitem_v['cti_icon_url_60']; ?>" rel='lazy'
                                src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif"/> </span> <?php } ?> </div>
                    <?php } ?><!--<span class="raty" data-score="<?php echo $value['evaluation_good_star']; ?>"></span>-->
                </div>
                <div class="goods-sub">
                    <?php if ($value['is_virtual'] == 1){ ?><span class="virtual" title="虚拟兑换商品">虚拟兑换</span><?php } ?>
                    <?php if ($value['is_fcode'] == 1){ ?><span class="fcode" title="F码优先购买商品">F码优先</span> <?php } ?>
                    <?php if ($value['is_book'] == 1){ ?><span class="book" title="支付定金预定商品">预定</span> <?php } ?>
                    <?php if ($value['is_presell'] == 1){ ?><span class="presell" title="预售购买商品">预售</span><?php } ?>
                    <?php if ($value['have_gift'] == 1){ ?><span class="gift" title="捆绑赠品">赠品</span> <?php } ?>
                </div>
                <div class="add-cart"><?php if ($value['goods_storage'] == 0)
                    { ?><a href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1')
                    { ?>login_dialog();<?php }
                    else
                    { ?>ajax_form('arrival_notice', '到货通知', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php } ?>">
                            <i class="icon-bullhorn"></i>到货通知</a><?php }
                    else
                    { ?><?php if ($value['is_virtual'] == 1 || $value['is_fcode'] == 1 || $value['is_presell'] == 1 || $value['is_book'] == 1)
                    { ?> <a href="javascript:void(0);" yftype="buy_now"
                            data-param="{goods_id:<?php echo $value['goods_id']; ?>}"><i
                            class="icon-shopping-cart"></i><?php if ($value['is_fcode'] == 1)
                        {
                            echo 'F码购买';
                        }
                        else if ($value['is_book'] == 1)
                        {
                            echo '支付定金';
                        }
                        else if ($value['is_presell'] == 1)
                        {
                            echo '预售购买';
                        }
                        else
                        {
                            echo '立即购买';
                        } ?>  </a> <?php }
                    else
                    { ?><a href="javascript:void(0);" yftype="add_cart" data-gid="<?php echo $value['goods_id']; ?>"><i
                            class="icon-shopping-cart"></i>加入购物车</a> <?php }
                    } ?>  </div>
            </div>
        </div> </li><?php } ?>
        <div class="clear"></div></ul><?php }
    else
    { ?>
        <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record']; ?></div> <?php } ?></div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL; ?>/index.php" target="_blank"><input id="act"
                                                                                                            name="act"
                                                                                                            type="hidden"
                                                                                                            value="buy"/><input
        id="op" name="op" type="hidden" value="buy_step1"/><input id="goods_id" name="cart_id[]" type="hidden"/></form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            width: 80,
            score: function () {
                return $(this).attr('data-score');
            }
        });
    });
</script> 
