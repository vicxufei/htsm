<?php defined('ByShopWWI') or exit('Access Invalid!');?>

<div>
  <div class="cart-title">
    <h3><i class="fa fa-shopping-cart"></i><?php echo $lang['cart_index_ensure_order'];?></h3>
    <p>查看购物车商品清单，增加减少商品数量，并勾选想要的商品进入下一步操作。</p>
  </div>
  <form action="<?php echo urlShop('buy','buy_step1');?>" method="POST" id="form_buy" name="form_buy">
    <input type="hidden" value="1" name="ifcart">
    <input type="hidden" value="" name="ifchain" id="ifchain">
    <table class="cart-table table" yf_id="cart_table">
      <thead>
        <tr>
          <th class="w60">
              <label><input type="checkbox" checked value="1" id="selectAll">全选</label></th>
          <th></th>
          <th><?php echo $lang['cart_index_store_goods'];?></th>
          <th class="w150"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?></th>
          <th class="w100"><?php echo $lang['cart_index_amount'];?></th>
          <th class="w150"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?></th>
          <th class="w80 tl"><?php echo $lang['cart_index_handle'];?></th>
        </tr>
      </thead>
      <?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
      <?php $is_chain = false;?>
      <tbody>
        <tr>
          <th colspan="20"> <!-- S 店铺名称 -->
            <div class="cart-store-name">
                店铺：
                <a href="<?php echo urlShop('show_store','index',array('store_id'=>$store_id), $output['store_list'][$store_id]['store_domain']);?>"><?php echo $cart_list[0]['store_name']; ?></a>
                <span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span>
            </div>
            <!-- E 店铺名称 --> 
            <!-- S 店铺满即送 -->
            <?php if (isset($output['voucher_template'][$store_id])) {?>
            <div class="cart-store-sale" nctype="get_voucher"> <span>代金券</span>免费领取<i class="icon-angle-down"></i>
              <div class="combobox"><i></i>
                <?php foreach ($output['voucher_template'][$store_id] as $voucher) {?>
                <div class="combobox-list"> <span class="par">¥<?php echo $voucher['voucher_t_price'];?></span> <span class="rule">
                  <p>全店通用，满<?php echo ncPriceFormat($voucher['voucher_t_limit']);?>元可折扣<?php echo $voucher['voucher_t_price'];?>元</p>
                  <time>限<?php echo date('Y-m-d', $voucher['voucher_t_end_date']);?>前使用</time>
                  </span> <a data-tid="<?php echo $voucher['voucher_t_id'];?>" href="javascript:;">领取</a> </div>
                <?php }?>
              </div>
            </div>
            <?php }?>
            <!-- E 店铺代金券 -->
            <!-- S 店铺满金额包邮 -->
            <?php if (!empty($output['free_freight_list'][$store_id])) {?>
            <div class="cart-store-sale"><span>免运费</span><?php echo $output['free_freight_list'][$store_id];?></div>
            <?php } ?>
            
            <!-- S 店铺满金额包邮 --> </th>
        </tr>
        <!-- S one store list -->
        <?php foreach($cart_list as $cart_info) {?>
        <tr id="cart_item_<?php echo $cart_info['cart_id'];?>" cart_group="<?php echo $cart_info['cart_id'];?>" class="shop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>">
          <td><input type="checkbox" <?php echo $cart_info['state'] ? 'checked' : 'disabled';?> yf_id="cart_item_check" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" data_chain="<?php echo $cart_info['is_chain'];?>" data_store_id="<?php echo $store_id;?>" id="cart_id<?php echo $cart_info['cart_id'];?>" name="cart_id[]" class="checkbox mt10"
<?php if ($cart_info['jjgRank'] > 0) { ?>
    data-jjg="<?php echo $cart_info['jjgRank']; ?>"
<?php } ?> /></td>
          <?php if ($cart_info['is_chain']) {$is_chain = true;}?>
          <td><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="cart-goods-thumb"><img src="<?php echo thumb($cart_info);?>" alt="<?php echo $cart_info['goods_name']; ?>" /></a></td>
          <td><dl class="cart-goods-info">
              <dt>
                <a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank"><?php echo $cart_info['goods_name']; ?></a></dt>
              <dd class="goods-spec"><?php echo $cart_info['goods_spec'];?></dd>

              <?php if ($cart_info['is_chain']) {?>
              <!-- S 门店自提服务 -->
              <dd class="goods-chain"><i>*</i>(该商品支持<strong>门店自提</strong>服务)</dd>
              <!-- E 门店自提服务 -->
              <?php }?>

              <?php if (!empty($cart_info['gift_list'])) {?>
             <!-- S 商品赠品列表 -->
              <dd class="cart-goods-gift"> <span>赠</span>
                <ul class="cart-goods-gift-list">
                  <?php foreach ($cart_info['gift_list'] as $goods_info) { ?>
                  <li cart_group="<?php echo $cart_info['cart_id'];?>"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['gift_goodsid']));?>" target="_blank" class="thumb" title="赠品：<?php echo $goods_info['gift_goodsname']; ?> * <?php echo $goods_info['gift_amount'] * $cart_info['goods_num']; ?>"><img src="<?php echo cthumb($goods_info['gift_goodsimage'],60,$store_id);?>" alt="<?php echo $goods_info['gift_goodsname']; ?>" /></a>
                    <?php } ?>
                  </li>
                </ul>
              </dd>
             <!-- E 商品赠品列表 -->
              <?php  } ?>
            </dl></td>
          <td><!-- S 商品单价 -->
            
            <?php if (!empty($cart_info['xianshi_info'])) {?>
            <em class="goods-old-price tip" title="商品原价格"><?php echo $cart_info['goods_yprice']; ?></em>
            <?php } ?>
            <em id="item<?php echo $cart_info['cart_id']; ?>_price" class="goods-price"><?php echo $cart_info['goods_price']; ?></em> 
            <!-- E 商品单价 --> 
            <!-- S 商品促销-限时折扣 -->
            
            <?php if (!empty($cart_info['xianshi_info'])) {?>
            <dl class="ncc-goods-sale">
              <dt>商家促销<i class="icon-angle-down"></i></dt>
              <dd>
                <p>活动名称：限时折扣</p>
                <p>满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></p>
              </dd>
            </dl>
            <?php }?>
            
            <!-- E 商品促销-限时折扣 --> 
            <!-- S 商品促销-团购 -->
            
            <?php if ($cart_info['ifgroupbuy']) {?>
            <dl class="ncc-goods-sale">
              <dt>商家促销<i class="icon-angle-down"></i></dt>
              <dd>
                <p>活动名称：团购</p>
                <?php if ($cart_info['upper_limit']) {?>
                <p>最多限购：<strong><?php echo $cart_info['upper_limit']; ?></strong>件 </p>
                <?php } ?>
              </dd>
            </dl>
            <?php }?>
            
            <!-- S 商品促销-团购 --></td>
          <?php if ($cart_info['state']) {?>
          <td>
              <a href="JavaScript:void(0);" onclick="decrease_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="add-substract-key">-</a>
            <input id="input_item_<?php echo $cart_info['cart_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" orig="<?php echo $cart_info['goods_num']; ?>" changed="<?php echo $cart_info['goods_num']; ?>" onkeyup="change_quantity(<?php echo $cart_info['cart_id']; ?>, this);" type="text" class="text tc w20"/>
            <a href="JavaScript:void(0);" onclick="add_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="add-substract-key" >+</a></td>
          <?php } else {?>
          <td>无效
            <input type="hidden" value="<?php echo $cart_info['cart_id']; ?>" name="invalid_cart[]"></td>
          <?php }?>
          <td><?php if ($cart_info['state']) {?>
            <em id="item<?php echo $cart_info['cart_id']; ?>_subtotal" yf_id="each_goods_sum" class="goods-subtotal"><?php echo $cart_info['goods_total']; ?></em>
            <?php }?></td>
          <td class="tl td-border-right">
            <a href="javascript:void(0)" onclick="collect_goods('<?php echo $cart_info['goods_id']; ?>');">移入收藏夹</a><br/>
            <a href="javascript:void(0)" onclick="drop_cart_item(<?php echo $cart_info['cart_id']; ?>);"><?php echo $lang['cart_index_del'];?></a></td>
        </tr>

        <?php } ?>
        <!-- E one store list -->
        <tr>
          <td colspan="20"><?php if ($is_chain) { ?>
            <div class="ncc-chain-tip">
                <strong>*</strong>
                该订单中包含支持“门店自提”服务的商品，如需到店自提，请选择提交
                <a data_store_id="<?php echo $store_id ?>" yf_id="chain" href="javascript:void(0)"><i class="icon-truck"></i>门店自提订单</a>
            </div>
            <?php } ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20"><div class="cart-all-account"><?php echo $lang['cart_index_goods_sumary'];?><em id="cartTotal"><?php echo $output['cart_totals']; ?></em><?php echo $lang['currency_zh'];?></div>
            <a id="next_submit" href="javascript:void(0)" class="btn btn-danger"><?php echo $lang['cart_index_ensure_info'];?></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<div class="jjg-chooes-box" id="jjg-choose-container">
  <div class="title-bar">
    <h3>换购商品列表</h3>
    <a href="javascript:;" id="jjg-choose-container-close" class="close" title="关闭">&#215;</a></div>
  <div class="choose-inner" id="jjg-choose-container-inner">
    <table>
      <thead>
        <tr>
          <th class="w30"></th>
          <th colspan="2">换购商品</th>
          <th class="w80">换购价格</th>
          <th class="w80">换购数量</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
<table style="display:none;">
  <tbody id="jjg-choose-item-wrapper">
    <tr>
      <td><input type="checkbox"
                    data-jjg-leveled="%jjg_id%"
                    data-jjg-leveled-level="%jjg_level%"
                    data-jjg-leveled-sku="%id%"
                /></td>
      <td class="w40"><img alt="" data-src="%imgUrl%"/></td>
      <td class="tl"><a target="_blank" href="%url%" class="name">%name%</a></td>
      <td><em class="old">%price%</em><em>%jjg_price%</em></td>
      <td>X1</td>
    </tr>
  </tbody>
  <tbody id="jjg-header-wrapper">
    <tr class="jjg-xor-outer-false">
      <td colspan="20" class="td-border-left td-border-rihgt tl"><div class="jjg-xor-inner-false"><strong>【加价购】</strong>已购满 %m0% 元，再加 %p0% 元，即可换购商品。 </div>
        <div class="jjg-xor-inner-true">
            <strong>【加价购】</strong>已购满 <span data-jjg-header-mincost="%id%">%mincost%</span> 元，再加 <span data-jjg-header-plus="%id%">%plus%</span> 元，点击<a href="javascript:;" data-jjg-toggle="%id%" class="btn-mini ml5">换购商品<i class="icon-caret-down ml5"></i></a></div>
        <div data-jjg-result="%id%"> </div></td>
    </tr>
  </tbody>
  <tbody id="jjg-footer-wrapper">
    <tr>
      <td colspan="20" style="padding: 4px;"></td>
    </tr>
  </tbody>
  <tbody id="jjg-chosen-item-wrapper">
    <tr data-chosen-item="%jjg_id%" data-chosen-item-sku="%id%" class="bundling-list">
      <td class="td-border-left tree"><input type="hidden" name="jjg[]" value="%jjg_id%|%jjg_level%|%id%" /></td>
      <td><a class="cart-goods-thumb" href="%url%" target="_blank"><img alt="%name%" data-src="%imgUrl%"></a></td>
      <td class="tl"><dl class="cart-goods-info">
          <dt> <a href="%url%" target="_blank">%name%</a> </dt>
          <dd class="cart-goods-gift"> <span>已选换购</span> </dd>
        </dl></td>
      <td><em class="goods-price">%jjg_price%</em></td>
      <td>1</td>
      <td><em class="jjg-item-when-calculation goods-subtotal">%jjg_price%</em></td>
      <td class="td-border-right"></td>
    </tr>
  </tbody>
</table>
<script type="text/javascript">
$(function() {
	$('a[yf_id="chain"]').on('click',function(){
		var chains = [],store_id = $(this).attr('data_store_id');
		$('input[name="cart_id[]"]').each(function(){
    			if ($(this).attr('data_store_id') != store_id) {
    				$(this).attr('checked',false);
    			} else {
    				if ($(this).prop('checked') && $(this).attr('data_chain') == '1') {
    					chains.push($(this).val());
    				} else {
    					$(this).attr('checked',false);
        			}
    			}
		});
		if (chains.length > 0) {
			$('#ifchain').val('1');
			$('#form_buy').submit();
		} else {
			alert('请先选择支持门店自提的商品');
			$('#ifchain').val('');
		}
	});
    // 领取代金券
    $('div[nctype="get_voucher"]').on('click', 'a[data-tid]', function(){
        var _tid = $(this).attr('data-tid');
        ajaxget('index.php?act=voucher&op=getvouchersave&jump=0&tid=' + _tid);
    });
	//
	$('#jjg-choose-container-inner').perfectScrollbar({suppressScrollX:true});
});

// 加价购
window.jjgCallback = (function(jjgDetails) {
	
	

    // 各个活动的头部
    var headers = {};

    // 各个活动的头部
    var footers = {};

    // 页面上被删掉的活动
    var missings = {};

    // 最后一次操作各个活动的已选商品总金额
    var costs = {};

    // 最后一次操作各个活动的限换购数
    var maxes = {};

    // 获取指定活动当前已选商品总金额 并缓存结果
    var jjgCost = function(id) {
        if (missings[id]) {
            costs[id] = 0;
            return 0;
        }

        var $items = $(":checkbox[data-jjg="+id+"]");
        if ($items.length < 1) {
            missings[id] = true;
            costs[id] = 0;
            return 0;
        }

        var c = 0;
        $items.filter(':checked').parents('tr.shop-list').find("em[yf_id='each_goods_sum']").each(function() {
            c += parseFloat(this.innerHTML) || 0;
        });
        costs[id] = c;
        return c;
    };

    // 活动头部TPL
    var jjgHeaderTpl = $('#jjg-header-wrapper').html();
    var jjgFooterTpl = $('#jjg-footer-wrapper').html();

    // 根据金额设置指定活动的头部
    var jjgHeader = function(id, selectedCost) {
        if (missings[id] && headers[id]) {
            headers[id].remove();
            footers[id].remove();
            return;
        }

        var found = false;
        var r = {
            id: id,
            storeId: jjgDetails.cou[id].info.store_id,
            mincost: jjgDetails.cou[id].firstLevel.mincost,
            plus: jjgDetails.cou[id].firstLevel.plus
        };
        r.m0 = r.mincost;
        r.p0 = r.plus;

        if (selectedCost >= r.mincost) {
            found = true;
            $.each(jjgDetails.cou[id].levels || {}, function(k, v) {
                if (selectedCost < v.mincost) {
                    return false;
                }
                r.mincost = v.mincost;
                r.plus = v.plus;
            });
        }

        if (headers[id]) {
            headers[id].find('[data-jjg-header-mincost]').html(r.mincost);
            headers[id].find('[data-jjg-header-plus]').html(r.plus);
        } else {
            var s = jjgHeaderTpl.replace(/%(\w+)%/g, function(m, $1) {
                return r[$1];
            });
            var $tr = $(s);
            var $tr2 = $(jjgFooterTpl);
            $(":checkbox[data-jjg='"+id+"']:first").parents('tr.shop-list').before($tr);
            $(":checkbox[data-jjg='"+id+"']:last").parents('tr.shop-list').after($tr2);

            headers[id] = $tr;
            footers[id] = $tr2;
        }

        headers[id].removeClass('jjg-xor-outer-'+!found).addClass('jjg-xor-outer-'+found);

        return found;
    };

    // 设置指定活动的头部
    var jjgSet = function(id) {
        hideChoices();

        // 已选活动商品变化则需要重新换购
        var selectedCouSkus = {};
        $("[data-chosen-item='"+id+"']").each(function() {
            var sku = $(this).attr('data-chosen-item-sku');
            selectedCouSkus[sku] = true;
        });

        $("[data-chosen-item='"+id+"']").remove();

        var c = jjgCost(id);
        var found = jjgHeader(id, c);

        // 重新换购已换购商品
        if (found) {
            var lastLevelFound = 0;
            // 遍历寻找当前已选活动商品金额可以选择的换购
            $.each(jjgDetails.cou[id].levels, function(k, v) {
                // 不满足条件则跳出循环
                if (v.mincost > costs[id]) {
                    return false;
                }

                // 更新当前活动规则的最大换购数
                maxes[id] = v.maxcou;
                lastLevelFound = k;
            });

            if (!lastLevelFound) {
                return;
            }

            // 可选换购商品
            var availableCouSkus = jjgDetails.cou[id].levelSkus[lastLevelFound] || {};
            // 最大限制换购数
            var m = maxes[id] || 0;

            // 遍历已换购商品
            $.each(selectedCouSkus, function(kk, vv) {
                if (!availableCouSkus[kk]) {
                    return;
                }
                if (m > 0 && $("[data-chosen-item='"+id+"']").length >= m) {
                    return false;
                }

                // 重新换购已换购商品
                choiceRealTriggered(id, lastLevelFound, kk, true);
            });
        }
    }

    // 当前激活换购选择的活动ID 0为未激活任何活动换购选择
    var jjgCurrentId = 0;

    // 隐藏共用的换购选择框
    var hideChoices = function() {
        jjgCurrentId = 0;
        $('#jjg-choose-container').css({
            top: -1000,
            left: -1000
        });
    };

    // 绑定换购选择框关闭按钮事件
    $('#jjg-choose-container-close').click(hideChoices);

    // 换购条目TPL
    var itemTpl = $('#jjg-choose-item-wrapper').html();

    // 绑定未来各个活动头部中的“换购商品”按钮的点击事件
    $('[data-jjg-toggle]').live('click', function() {
        var id = $(this).attr('data-jjg-toggle');

        // 如果当前活动已激活选择换购 则隐藏换购选择框
        if (id == jjgCurrentId) {
            hideChoices();
            return;
        }

        // 设置当前激活选择的活动
        jjgCurrentId = id;

        // 设置选择框位置
        var o = $(this).offset();
        o.top += $(this).height();
        $('#jjg-choose-container').css({
            top: o.top+3,
            left: o.left
        });

        // 清空选择框
        var $table = $('#jjg-choose-container tbody').empty();

        var lastLevelFound = 0;
        // 遍历寻找当前已选活动商品金额可以选择的换购
        $.each(jjgDetails.cou[id].levels, function(k, v) {
            // 不满足条件则跳出循环
            if (v.mincost > costs[id]) {
                return false;
            }

            // 更新当前活动规则的最大换购数
            maxes[id] = v.maxcou;
            lastLevelFound = k;
        });

        // 遍历插入规则中的可选换购商品
        $.each(jjgDetails.cou[id].levelSkus[lastLevelFound] || {}, function(kk, vv) {
            var r = $.extend({
                jjg_id: id,
                jjg_level: lastLevelFound,
                jjg_price: vv.price
            }, jjgDetails.items[kk]);

            var s = itemTpl.replace(/%(\w+)%/g, function(m, $1) {
                return r[$1];
            });

            var $s = $(s);
            $s.find('img').each(function() {
                this.src = $(this).attr('data-src');
            });

            $table.append($s);
        });

        // 设置已选换购商品为选中 并且触发选中事件
        $("[data-chosen-item='"+id+"']").each(function() {
            var sku = $(this).attr('data-chosen-item-sku');
            $table.find("[data-jjg-leveled-sku='"+sku+"']").each(function() {
                // 如果当前换购不可选 则跳出循环 正常不会有这种情况出现
                if (this.disabled) {
                    return false;
                }
                this.checked = true;
                choiceTriggered(this);
            });
        });
    });

    // 已选择换购条目TPL
    var chosenItemTpl = $('#jjg-chosen-item-wrapper').html();

    // 换购条目复选框被点击需要触发的操作
    var choiceTriggered = function(element) {
        var id = $(element).attr('data-jjg-leveled');
        var level = $(element).attr('data-jjg-leveled-level');
        var sku = $(element).attr('data-jjg-leveled-sku');
        var elementChecked = element.checked;
        choiceRealTriggered(id, level, sku, elementChecked);
    };

    var choiceRealTriggered = function(id, level, sku, elementChecked) {
        var m = maxes[id];

        if (m > 0) {
            var $leveled = $(":checkbox[data-jjg-leveled='"+id+"']");
            if ($leveled.filter(':checked').length >= m) {
                $leveled.not(':checked').attr('disabled', true);
            } else {
                $leveled.removeAttr('disabled');
            }
        }

        $("[data-chosen-item='"+id+"'][data-chosen-item-sku='"+sku+"']").remove();
        if (elementChecked) {
            var r = $.extend({
                jjg_id: id,
                jjg_level: level,
                jjg_price: jjgDetails.cou[id].levelSkus[level][sku].price
            }, jjgDetails.items[sku]);
            var s = chosenItemTpl.replace(/%(\w+)%/g, function(mat, $1) {
                return r[$1];
            });

            var $s = $(s);
            $s.find('img').each(function() {
                this.src = $(this).attr('data-src');
            });

            footers[id].before($s);
        }

        // 执行外部函数 重新计算总价
        if (window.jjgRecalculator) {
            window.jjgRecalculator();
        }
    };

    // 绑定未来换购复选框点击事件
    $(':checkbox[data-jjg-leveled]').live('click', function() {
        choiceTriggered(this);
    });

    // 导出函数 外部使用
    return function(jjgId) {
        if (jjgId < 0) {
            return;
        }

        if (jjgId > 0) {
            jjgSet(jjgId);
            return;
        }

        $.each(jjgDetails.cou || {}, function(k, v) {
            jjgSet(k);
        });
    };

})(<?php echo json_encode((array) $output['jjgDetails']); ?>);

</script> 
