<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div class="row"><?php if (!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
        <?php foreach ($output['goods_list'] as $value){ ?>
            <div class="col-md-3 good-item">
                <div class="thumbnail">
                    <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $value['goods_id'])); ?>" target="_blank" title="<?php echo $value['goods_name']; ?>">
                        <img data-url="<?php echo yf_cthumb($value['goods_image'], 220, $value['store_id']); ?>"
                             rel="lazy"
                             src="<?php echo SHOP_SITE_URL; ?>/img/loading.gif"
                             title="<?php echo $value['goods_name']; ?>"
                             alt="<?php echo $value['goods_name']; ?>"/>
                    </a>
                    <?php if ($value['goods_promotion_type'] == 1){ ?><div class="goods-promotion"><span>团购商品</span></div><?php }
                    elseif ($value['goods_promotion_type'] == 2){ ?><div class="goods-promotion"><span>限时折扣</span></div><?php } ?>
                    <div class="caption">
                        <div class="good-price">
                            <span class="p-normal"
                                  title="<?php echo $lang['goods_class_index_store_goods_price'] . $lang['nc_colon'] . $lang['currency'] . ncPriceFormat($value['goods_promotion_price']); ?>">
                                <?php echo ncPriceFormatForList($value['goods_promotion_price']); ?>
                            </span>
                            <span class="p-del"
                                  title="市场价：<?php echo $lang['currency'] . $value['goods_marketprice']; ?>">
                                <?php echo ncPriceFormatForList($value['goods_marketprice']); ?>
                            </span>
                        </div>
                        <div class="good-name">
                            <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $value['goods_id'])); ?>"
                                target="_blank"
                                title="<?php echo $value['goods_jingle']; ?>">
                                <?php echo $value['goods_name_highlight']; ?>
                            </a>
                            <mark>
                                <?php $goods_attr=unserialize($value['goods_attr']);
                                if (is_array($goods_attr) && !empty($goods_attr)){
                                    foreach ($goods_attr as $v){
                                        $val = array_values($v);
                                       // echo '['.$val[0].'-'.$val[1].']';
                                        echo $val[1];
                                    }
                                } ?>
                                <?php echo $value['goods_jingle']; ?>
                            </mark>
                        </div>
                        <div class="good-buy">
                            <?php if ($value['goods_storage'] == 0){ ?>
                            <a class="btn btn-danger" href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1'){ ?>login_dialog();<?php } else{ ?>
                                ajax_form('arrival_notice', '到货通知', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php } ?>">到货通知</a><?php } else{ ?><?php if ($value['is_virtual'] == 1 || $value['is_book'] == 1)
                            { ?> <a class="btn btn-danger" href="javascript:void(0);" yftype="buy_now"
                                    data-param="{goods_id:<?php echo $value['goods_id']; ?>}">
                                <i class="icon-shopping-cart"></i>立即购买 </a> <?php }
                            else
                            { ?><a class="btn btn-danger btn-block" href="javascript:void(0);" yftype="add_cart" data-gid="<?php echo $value['goods_id']; ?>"><i
                                    class="icon-shopping-cart"></i>加入购物车</a> <?php }
                            } ?>
                        </div>
                    </div>
            </div></div><?php } ?>
        <?php }
    else{ ?>
        <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record']; ?></div> <?php } ?></div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL; ?>/index.php" target="_blank">
    <input id="act" name="act" type="hidden" value="buy"/>
    <input id="op" name="op" type="hidden" value="buy_step1"/>
    <input id="goods_id" name="cart_id[]" type="hidden"/>
</form>
