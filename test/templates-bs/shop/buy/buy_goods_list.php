<?php defined('ByShopWWI') or exit('Access Invalid!'); ?>
<div class="receipt-info">
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th><?php echo $lang['cart_index_store_goods']; ?></th>
            <th><?php echo $lang['cart_index_price'] . '(' . $lang['currency_zh'] . ')'; ?></th>
            <th><?php echo $lang['cart_index_amount']; ?></th>
            <th><?php echo $lang['cart_index_sum'] . '(' . $lang['currency_zh'] . ')'; ?></th>
        </tr>
        </thead>
        <tbody id="jjg-valid-skus-tpl" style="display:none;">
        <tr class="bundling-list">
            <td class="tree td-border-left"><input name="jjg[]" type="hidden" value="%jjgId%|%jjgLevel%|%id%"/></td>
            <td><a class="cart-goods-thumb" href="%url%" target="_blank"> <img alt="%name%" data-src="%imgUrl%"/> </a>
            </td>
            <td class="tl">
                <dl class="cart-goods-info">
                    <dt><a href="%url%" target="_blank">%name%</a></dt>
                    <dd class="cart-goods-gift"><span>已选换购</span></dd>
                </dl>
            </td>
            <td><em class="goods-price">%jjgPrice%</em></td>
            <td>1</td>
            <td class="td-border-right"><em nc_type="eachGoodsTotal" class="goods-subtotal"> %jjgPrice% </em></td>
        </tr>
        </tbody>
        <?php foreach ($output['store_cart_list'] as $store_id => $cart_list)
        { ?>
        <tbody>
        <tr>
            <th colspan="20"> <!-- S 店铺名称 -->
                <div class="cart-store-name">店铺：<a
                        href="<?php echo urlShop('show_store', 'index', array('store_id' => $store_id)); ?>"><?php echo $cart_list[0]['store_name']; ?></a>
                    <span member_id="<?php echo $output['store_list'][$store_id]['member_id']; ?>"></span></div>
                <!-- E 店铺名称 -->

                <!-- S 店铺满金额包邮 -->
                <?php if (!empty($output['cancel_calc_sid_list'][$store_id]))
                { ?>
                    <div class="cart-store-sale">
                        <span>免运费</span><?php echo $output['cancel_calc_sid_list'][$store_id]['desc']; ?></div>
                <?php } ?>

                <!-- S 店铺满金额包邮 --> </th>
        </tr>
        <?php foreach ($cart_list as $cart_info)
        { ?>
            <tr id="cart_item_<?php echo $cart_info['cart_id']; ?>"
                class="shop-list <?php echo ($cart_info['state'] && $cart_info['storage_state']) ? '' : 'item_disabled'; ?>"
                <?php if ($cart_info['jjgRank'] > 0)
                { ?>
                    data-jjg="<?php echo $cart_info['jjgRank']; ?>"
                <?php } ?>
                >
                <td class="td-border-left
		<?php if ($cart_info['jjgRank'] > 0)
                { ?>
        td-bl
		<?php } ?>"><?php if ($cart_info['state'] && $cart_info['storage_state'])
                    { ?>
                        <input type="hidden"
                               value="<?php echo $cart_info['cart_id'] . '|' . $cart_info['goods_num']; ?>"
                               store_id="<?php echo $store_id ?>" name="cart_id[]">
                        <input type="hidden"
                               value="<?php echo $cart_info['goods_id'] . '|' . $cart_info['goods_num']; ?>"
                               store_id="<?php echo $store_id ?>" name="goods_id[]">
                    <?php } ?></td>
                    <td class="w100"><a
                            href="<?php echo urlShop('goods', 'index', array('goods_id' => $cart_info['goods_id'])); ?>"
                            target="_blank" class="cart-goods-thumb"><img src="<?php echo thumb($cart_info); ?>"
                                                                          alt="<?php echo $cart_info['goods_name']; ?>"/></a>
                    </td>
                <td class="tl">
                    <dl class="cart-goods-info">
                        <dt>
                            <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $cart_info['goods_id'])); ?>"
                               target="_blank"><?php echo $cart_info['goods_name']; ?></a></dt>
                            <dd class="goods-spec"><?php echo $cart_info['goods_spec']; ?></dd>
 <!-- S 商品赠品列表 -->
                        <?php if (!empty($cart_info['gift_list']))
                        { ?>
                            <dd class="cart-goods-gift"><span>赠品</span>
                                <ul class="cart-goods-gift-list">
                                    <?php foreach ($cart_info['gift_list'] as $goods_info)
                                    { ?>
                                        <li nc_group="<?php echo $cart_info['cart_id']; ?>"><a
                                                href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_info['gift_goodsid'])); ?>"
                                                target="_blank" class="thumb"
                                                title="赠品：<?php echo $goods_info['gift_goodsname']; ?> * <?php echo $goods_info['gift_amount'] * $cart_info['goods_num']; ?>"><img
                                                    src="<?php echo cthumb($goods_info['gift_goodsimage'], 60, $store_id); ?>"
                                                    alt="<?php echo $goods_info['gift_goodsname']; ?>"/></a></li>
                                    <?php } ?>
                                </ul>
                            </dd>
                        <?php } ?>
                        <!-- E 商品赠品列表 -->
                    </dl>
                </td>
                <td><!-- S 商品单价 -->

                    <?php if (!empty($cart_info['xianshi_info']))
                    { ?>
                        <em class="goods-old-price tip" title="商品原价格"><?php echo $cart_info['goods_yprice']; ?></em>
                    <?php } ?>
                    <em class="goods-price"><?php echo $cart_info['goods_price']; ?></em><!-- E 商品单价 -->
                    <!-- S 商品促销-限时折扣 -->

                    <?php if (!empty($cart_info['xianshi_info']))
                    { ?>
                        <dl class="cart-goods-sale">
                            <dt>商家促销<i class="icon-angle-down"></i></dt>
                            <dd>
                                <p>活动名称：限时折扣</p>

                                <p>
                                    满<strong><?php echo $cart_info['xianshi_info']['lower_limit']; ?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em>
                                </p>
                            </dd>
                        </dl>
                    <?php } ?>

                    <!-- E 商品促销-限时折扣 -->
                    <!-- S 商品促销-团购 -->

                    <?php if ($cart_info['ifgroupbuy'])
                    { ?>
                        <dl class="cart-goods-sale">
                            <dt>商家促销<i class="icon-angle-down"></i></dt>
                            <dd>
                                <p>活动名称：团购</p>
                                <?php if ($cart_info['upper_limit'])
                                { ?>
                                    <p>最多限购：<strong><?php echo $cart_info['upper_limit']; ?></strong>件 </p>
                                <?php } ?>
                            </dd>
                        </dl>
                    <?php } ?>

                    <!-- E 商品促销-团购 -->
                    <!-- E 促销活动-团购 --></td>
                <td><?php echo $cart_info['state'] ? $cart_info['goods_num'] : ''; ?></td>
                <td class="td-border-right"><?php if ($cart_info['state'] && $cart_info['storage_state'])
                    { ?>
                        <em cart_id="<?php echo $cart_info['cart_id']; ?>"
                            goods_id="<?php echo $cart_info['goods_id']; ?>"
                            nc_type="eachGoodsTotal<?php echo $store_id ?>"
                            tpl_id="<?php echo $cart_info['transport_id'] ?>"
                            class="goods-subtotal"><?php echo $cart_info['goods_total']; ?></em> <span
                        id="no_send_tpl_<?php echo $cart_info['transport_id'] ?>"
                        style="color: #F00;display:none">无货</span>
                    <?php }
                    elseif (!$cart_info['storage_state'])
                    { ?>
                        <span style="color: #F00;">库存不足</span>
                    <?php }
                    elseif (!$cart_info['state'])
                    { ?>
                        <span style="color: #F00;">无效</span>
                    <?php } ?></td>
            </tr>

        <?php } ?>
        <tr>
            <td colspan="30">
                <div class="cart-msg">买家留言：
                    <textarea
                        name="pay_message[<?php echo $store_id; ?>]"
                        class="cart-msg-textarea" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"
                        title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" maxlength="150"></textarea>
                </div>
                <div class="cart-store-account">
                    <dl>
                        <dt>商品金额：</dt>
                        <dd class="rule"></dd>
                        <dd class="sum"><em
                                id="eachStoreGoodsTotal_<?php echo $store_id; ?>"><?php echo $output['store_goods_total'][$store_id]; ?></em>
                        </dd>
                    </dl>

                    <!-- S voucher list -->

                    <?php if (!empty($output['store_voucher_list'][$store_id]) && is_array($output['store_voucher_list'][$store_id])){ ?>
                        <dl>
                            <dt>优惠卡券：</dt>
                            <dd class="rule">
                                <select nctype="voucher" name="voucher[<?php echo $store_id; ?>]" class="select">
                                    <option
                                        value="<?php echo $voucher['voucher_t_id']; ?>|<?php echo $store_id; ?>|0.00">
                                        -选择使用店铺代金券-
                                    </option>
                                    <?php foreach ($output['store_voucher_list'][$store_id] as $voucher)
                                    { ?>
                                        <option
                                            value="<?php echo $voucher['voucher_t_id']; ?>|<?php echo $store_id; ?>|<?php echo $voucher['voucher_price']; ?>"><?php echo $voucher['desc']; ?></option>
                                    <?php } ?>
                                </select>
                            </dd>
                            <dd class="sum"><em id="eachStoreVoucher_<?php echo $store_id; ?>"
                                                class="subtract">-0.00</em></dd>
                        </dl>
                        <!-- E voucher list -->
                    <?php } ?>
                    <dl>
                        <dt>物流运费：</dt>
                        <dd class="rule">
                            <?php if (!empty($output['cancel_calc_sid_list'][$store_id]))
                            { ?>
                                <?php echo $output['cancel_calc_sid_list'][$store_id]['desc']; ?>
                            <?php } ?>
                        </dd>
                        <dd class="sum"><em nc_type="eachStoreFreight" id="eachStoreFreight_<?php echo $store_id; ?>">0.00</em>
                        </dd>
                    </dl>
                    <dl class="total">
                        <dt>本店合计：</dt>
                        <dd class="rule"></dd>
                        <dd class="sum"><em store_id="<?php echo $store_id; ?>"
                                            nc_type="eachStoreTotal"></em><?php echo $lang['currency_zh']; ?></dd>
                    </dl>
                </div>
            </td>
        </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <!-- S rpt list -->
        <tr id="rpt_panel" style="display: none">
            <td class="pd-account" colspan="20">
                <div class="cart-store-account">
                    <dl>
                        <dt>平台红包：</dt>
                        <dd class="rule">
                            <select nctype="rpt" id="rpt" name="rpt" class="select">
                            </select>
                        <dd class="sum"><em id="orderRpt" class="subtract">-0.00</em></dd>
                    </dl>
                </div>
            </td>
        </tr>
        <!-- E rpt list -->
        <tr>
            <td colspan="20"><?php if (!empty($output['ifcart']))
                { ?>
                    <a href="index.php?act=cart" class="cart-prev-btn"><i
                            class="icon-angle-left"></i><?php echo $lang['cart_step1_back_to_cart']; ?></a>
                <?php } ?>
                <div class="cart-all-account">订单总金额：<em
                        id="orderTotal">....</font></em><?php echo $lang['currency_zh']; ?></div>
                <a href="javascript:void(0)" id='submitOrder'
                   class="cart-next-submit"><?php echo $lang['cart_index_submit_order']; ?></a></td>
        </tr>
        </tfoot>
    </table>
</div>
<script>
    function submitNext() {
        if (!SUBMIT_FORM) return;

        if ($('input[name="cart_id[]"]').size() == 0) {
            showDialog('所购商品无效', 'error', '', '', '', '', '', '', '', '', 2);
            return;
        }
        if ($('#address_id').val() == '') {
            showDialog('<?php echo $lang['cart_step1_please_set_address'];?>', 'error', '', '', '', '', '', '', '', '', 2);
            return;
        }
        if ($('#buy_city_id').val() == '') {
            showDialog('正在计算运费,请稍后！', 'error', '', '', '', '', '', '', '', '', 2);
            return;
        }
        if ($('input[name="fcode"]').size() == 1 && $('#fcode_callback').val() != '1') {
            showDialog('请输入并使用F码！', 'error', '', '', '', '', '', '', '', '', 2);
            return;
        }
        if (no_send_tpl_ids.length > 0 || no_chain_goods_ids.length > 0) {
            showDialog('有部分商品配送范围无法覆盖您选择的地址，请更换其它商品！', 'error', '', '', '', '', '', '', '', '', 4);
            return;
        }
        SUBMIT_FORM = false;
        $('#order_form').submit();
    }

    //计算总运费和每个店铺小计
    function calcOrder() {
        allTotal = 0;
        $('em[nc_type="eachStoreTotal"]').each(function () {
            store_id = $(this).attr('store_id');
            var eachTotal = 0;
            $('em[nc_type="eachGoodsTotal' + store_id + '"]').each(function () {
                if (no_send_tpl_ids[$(this).attr('tpl_id')]) {
                    $(this).next().show();
                    $('#cart_item_' + $(this).attr('cart_id')).addClass('item_disabled');
                } else {
                    if (no_chain_goods_ids[$(this).attr('goods_id')]) {
                        $(this).next().show();
                        $('#cart_item_' + $(this).attr('cart_id')).addClass('item_disabled');
                    } else {
                        $(this).next().hide();
                        $('#cart_item_' + $(this).attr('cart_id')).removeClass('item_disabled');
                    }
                }
            });
            if ($('#eachStoreGoodsTotal_' + store_id).length > 0) {
                eachTotal += parseFloat($('#eachStoreGoodsTotal_' + store_id).html());
            }

            if ($('#eachStoreVoucher_' + store_id).length > 0) {
                eachTotal += parseFloat($('#eachStoreVoucher_' + store_id).html());
            }
            if ($('#eachStoreFreight_' + store_id).length > 0) {
                eachTotal += parseFloat($('#eachStoreFreight_' + store_id).html());
            }
            allTotal += eachTotal;
            $(this).html(eachTotal.toFixed(2));
        });

        if ($('#orderRpt').length > 0) {
            iniRpt(allTotal.toFixed(2));
            $('#orderRpt').html('-0.00');
        }
        $('#orderTotal').html(allTotal.toFixed(2));
        $('#submitOrder').on('click', function () {
            submitNext()
        }).addClass('ok');
    }
    $(function () {
        var tpl = $('#jjg-valid-skus-tpl').html();
        var jjgValidSkus = <?php echo json_encode($output['jjgValidSkus']); ?>;

        $footers = {};
        $('[data-jjg]').each(function () {
            var id = $(this).attr('data-jjg');
            if (!$footers[id]) {
                var $footer = $('<tr><td colspan="20"></td></tr>');
                $footers[id] = $footer;
                $("tr[data-jjg='" + id + "']:last").after($footer);
            }
        });

        $.each(jjgValidSkus || {}, function (k, v) {
            $.each(v || {}, function (kk, vv) {
                var s = tpl.replace(/%(\w+)%/g, function ($m, $1) {
                    return vv[$1];
                });
                var $s = $(s);
                $s.find('img[data-src]').each(function () {
                    this.src = $(this).attr('data-src');
                });
                $footers[k].before($s);
            });
        });
    });

</script> 
