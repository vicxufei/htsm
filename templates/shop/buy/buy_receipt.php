<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<!-- 收货人 S -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            收货人信息<span class="badge"><a href="javascript:void(0)" yf_id="buy_edit" id="edit_reciver">修改</a></span>
        </h3>
    </div>
    <div id="addr_list" class="addr-list">
        <ul class="list-group">
            <li class="list-group-item">
                    <strong><?php echo $output['address_info']['true_name'];?></strong>
                    <?php echo $output['address_info']['area_info'];?>,<?php echo $output['address_info']['address'];?>
                    <i class="glyphicon glyphicon-earphone"></i> <?php echo $output['address_info']['mob_phone'] ? $output['address_info']['mob_phone'] : $output['address_info']['tel_phone'];?>
            </li>
        </ul>
    </div>
</div>

<!-- 收货人 E -->
<!-- 支付方式 S -->
<div class="panel panel-default" id="paymentCon">
    <div class="panel-heading">
        <h3 class="panel-title">
            支付方式<span class="badge">
                <?php if (!$output['deny_edit_payment']) {?>
                    <a href="javascript:void(0)" yf_id="buy_edit" id="edit_payment">修改</a>
                <?php }?>
            </span>
        </h3>
    </div>
    <div id="payment_list" style="display:none">
        <div>
            <input type="radio" value="online" name="payment_type" id="payment_type_online">
            <label for="payment_type_online">在线支付</label>
        </div>
        <?php if ($output['ifshow_chainpay']) { ?>
        <div>
            <input type="radio" value="chain" name="payment_type" id="payment_type_chain">
            <label for="payment_type_chain">门店支付</label>
        </div>
        <?php } ?>
        <div>
            <input type="radio" value="offline" name="payment_type" id="payment_type_offline">
            <label for="payment_type_offline">货到付款</label>
            <a id="show_goods_list" style="display: none" class="payment-goods" href="javascript:void(0);">
                <i class="fa fa-truckicon"></i>货到付款 (<span data-cod-nums="offline"><?php echo count($output['pay_goods_list']['offline']);?></span>种商品)
                + <i class="fa fa-credit-card"></i>在线支付 (<span data-cod-nums="online"><?php echo count($output['pay_goods_list']['online']);?></span>种商品)
            </a>
        </div>
        <div class="hr16"> <a href="javascript:void(0);" class="btn btn-default" id="hide_payment_list">保存支付方式</a></div>
    </div>
    <div id="payment_goods_list" class="payment-goods-list">
        <dl>
            <dt data-hideshow="offline">货到付款</dt>
            <dd data-hideshow="offline" data-cod2-type="offline">
                <?php foreach((array) $output['pay_goods_list']['offline'] as $value) {?>
                    <div class="goods-thumb" data-cod2-store="<?php echo $value['store_id']; ?>"><span><img src="<?php echo thumb($value,60);?>"></span></div>
                <?php } ?>
            </dd>
            <dt data-hideshow="online">在线支付</dt>
            <dd data-hideshow="online" data-cod2-type="online">
                <?php foreach((array) $output['pay_goods_list']['online'] as $value) {?>
                    <div class="goods-thumb" data-cod2-store="<?php echo $value['store_id']; ?>"><span><img src="<?php echo thumb($value,60);?>"></span></div>
                <?php } ?>
            </dd>
        </dl>
    </div>
</div>
<!-- 在线支付和货到付款组合时，显示弹出确认层内容 -->
<div id="confirm_offpay_goods_list" style="display: none;">
    <dl class="offpay-list" data-hideshow="offline">
        <dt>以下商品支持<strong>货到付款</strong></dt>
        <dd>
            <ul data-cod-type="offline">
                <?php foreach((array) $output['pay_goods_list']['offline'] as $value) {?>
                    <li data-cod-store="<?php echo $value['store_id']; ?>"><span title="<?php echo $value['goods_name'];?>"><img src="<?php echo thumb($value,60);?>"></span></li>
                <?php } ?>
            </ul>
            <label>
                <input type="radio" value="" checked="checked">
                货到付款
            </label>
        </dd>
    </dl>
    <dl class="offpay-list" data-hideshow="online">
        <dt>以下商品支持<strong>在线支付</strong></dt>
        <dd>
            <ul data-cod-type="online">
                <?php foreach((array) $output['pay_goods_list']['online'] as $value) {?>
                    <li data-cod-store="<?php echo $value['store_id']; ?>"><span title="<?php echo $value['goods_name'];?>"><img src="<?php echo thumb($value,60);?>"></span></li>
                <?php } ?>
            </ul>
            <label>
                <input type="radio" value="" checked="checked">
                在线支付
            </label>
        </dd>
    </dl>
    <div class="tc mt10 mb10"><a href="javascript:void(0);" class="ncbtn ncbtn-bittersweet" id="close_confirm_button">确认支付方式</a></div>
</div>
<!-- 支付方式  -->
<!-- 发票信息 S -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">发票信息<span class="badge"><a href="javascript:void(0)" yf_id="buy_edit" id="edit_invoice">修改</a></span></h3>
    </div>
    <div id="invoice_list">
        <ul class="list-group">
            <li class="list-group-item"><?php echo $output['inv_info']['content']; ?></li>
        </ul>
    </div>
</div>
<!-- 发票信息 S -->

<script type="text/javascript">
    //隐藏收货地址列表
    function hideAddrList(addr_id,true_name,address,phone) {
        $('#edit_reciver').show();
        $("#address_id").val(addr_id);
        $("#addr_list").html('<ul class="list-group"><li class="list-group-item"><span class="true-name">'+true_name+'</span><span class="address">'+address+'</span><span class="phone"><i class="fa fa-mobile"></i>'+phone+'</span></li></ul>');
        $('.current_box').removeClass('current_box');
        ableOtherEdit();
        $('#edit_payment').click();
    }
    //加载收货地址列表
    $('#edit_reciver').on('click',function(){
        $(this).hide();
        disableOtherEdit();
        $(this).parent().parent().addClass('current_box');
        var url = SITEURL+'/index.php?act=buy&op=load_addr';
        <?php if ($output['ifshow_chainpay']) { ?>
        url += '&ifchain=1';
        <?php } ?>
        $('#addr_list').load(url);
    });
    //异步显示每个店铺运费 city_id计算运费area_id计算是否支持货到付款
    function showShippingPrice(city_id,area_id) {
        $('#buy_city_id').val('');
        $.post(SITEURL + '/index.php?XDEBUG_SESSION_START=PHPSTORM&act=buy&op=change_addr', {'store_goods_weight':'<?php echo json_encode($output['store_goods_weight'],JSON_UNESCAPED_UNICODE);?>','freight_hash':'<?php echo $output['freight_hash'];?>','city_id':city_id,'area_id':area_id}, function(data){
            console.log(data);
            if(data.state == 'success') {
                $('#buy_city_id').val(area_id);
                $('#allow_offpay').val(data.allow_offpay);
                if (data.allow_offpay_batch) {
                    var arr = new Array();
                    $.each(data.allow_offpay_batch, function(k, v) {
                        arr.push('' + k + ':' + (v ? 1 : 0));
                    });
                    $('#allow_offpay_batch').val(arr.join(";"));
                }
                $('#offpay_hash').val(data.offpay_hash);
                $('#offpay_hash_batch').val(data.offpay_hash_batch);
                var content = data.content;var tpl_ids = data.no_send_tpl_ids;
                no_send_tpl_ids = [];no_chain_goods_ids = [];
                for(var i in content){
                    if (content[i] !== false) {
                        $('#eachStoreFreight_'+i).html(number_format(content[i],2));
                    } else {
                        no_send_store_ids[i] = true;
                    }
                }
                for(var i in tpl_ids){
                    no_send_tpl_ids[tpl_ids[i]] = true;
                }
                calcOrder();
            } else {
                showDialog('系统出现异常', 'error','','','','','','','','',2);
            }

        },'json');
    }

    //根据门店自提站ID计算商品是否有库存（有库存即支持自提）
    function showProductChain(city_id) {
        $('#buy_city_id').val('');
        var product = [];
        $('input[name="goods_id[]"]').each(function(){
            product.push($(this).val());
        });
        $.post(SITEURL+'/index.php?act=buy&op=change_chain',{chain_id:chain_id,product:product.join('-')},function(data){
            if (data.state == 'success') {
                $('#buy_city_id').val(city_id);
                $('em[nc_type="eachStoreFreight"]').html('0.00');
                no_send_tpl_ids = [];no_chain_goods_ids = [];
                if (data.product.length > 0) {
                    for (var i in data.product) {
                        no_chain_goods_ids[data.product[i]] = true;
                    }
                }
                calcOrder();
            } else {
                showDialog('系统出现异常', 'error','','','','','','','','',2);
            }
        },'json');
    }
    $(function(){
        <?php if (!empty($output['address_info']['address_id'])) {?>
        showShippingPrice(<?php echo $output['address_info']['city_id'];?>,<?php echo $output['address_info']['area_id'];?>);
        <?php } else {?>
        $('#edit_reciver').click();
        <?php }?>
    });
</script>
<script type="text/javascript">
    $(function(){

        var hybrid = <?php echo $output['ifshow_offpay'] === true && count($output['pay_goods_list']['online']) > 0 ? '1' : '0'; ?>;

        var failInPage = false;

// 重新调整在线支付/到付的商品展示
        var setCodGoodsShow = function() {
            var j = $('#allow_offpay_batch').val();
            var arr = {};
            if (j) {
                $.each(j.split(';'), function(k, v) {
                    vv = v.split(':');
                    arr[vv[0]] = vv[1] == '1' ? true : false;
                });
            }

            $.each(arr, function(k, v) {
                //console.log(''+k+':'+v);
                if (v) {
                    $("[data-cod-type='online'] [data-cod-store='"+k+"']").appendTo("[data-cod-type='offline']");
                    $("[data-cod-type='online'] [data-cod-store='"+k+"']").remove();

                    $("[data-cod2-type='online'] [data-cod2-store='"+k+"']").appendTo("[data-cod2-type='offline']");
                    $("[data-cod2-type='online'] [data-cod2-store='"+k+"']").remove();
                } else {
                    $("[data-cod-type='offline'] [data-cod-store='"+k+"']").appendTo("[data-cod-type='online']");
                    $("[data-cod-type='offline'] [data-cod-store='"+k+"']").remove();

                    $("[data-cod2-type='offline'] [data-cod2-store='"+k+"']").appendTo("[data-cod2-type='online']");
                    $("[data-cod2-type='offline'] [data-cod2-store='"+k+"']").remove();
                }
            });

            var off = $("[data-cod2-type='offline'] [data-cod2-store]").length;
            var on = $("[data-cod2-type='online'] [data-cod2-store]").length;

            $("[data-hideshow='offline']")[off ? 'show' : 'hide']();
            $("[data-hideshow='online']")[on ? 'show' : 'hide']();

            $("span[data-cod-nums='offline']").html(off);
            $("span[data-cod-nums='online']").html(on);

            failInPage = ! off;
            hybrid = off && on;

        };

        //点击修改支付方式
        $('#edit_payment').on('click',function(){
            $('#edit_payment').parent().next().remove();
            $(this).hide();
            $('#paymentCon').addClass('current_box');
            $('#payment_list').show();
            if (chain_id != '') {
                $('#payment_type_offline').parent().hide();
                $('#payment_type_chain').parent().show();
            } else {
                $('#payment_type_online').attr('checked',true);
                $('#payment_type_chain').parent().hide();
                $('#payment_type_offline').parent().show();
            }
            disableOtherEdit();
        });
        //保存支付方式
        $('#hide_payment_list').on('click',function(){
            var payment_type = $('input[name="payment_type"]:checked').val();
            if ($('input[name="payment_type"]:checked').size() == 0) return;

            setCodGoodsShow();

            //判断该地区(县ID)是否能货到付款
            if (payment_type == 'offline' && ($('#allow_offpay').val() == '0' || failInPage)) {
                showDialog('您目前选择的收货地区不支持货到付款!', 'error','','','','','','','','',2);return;
            }
            $('#payment_list').hide();
            $('#edit_payment').show();
            $('.current_box').removeClass('current_box');
            if (payment_type == 'chain') {
                var content = '门店支付';
            } else {
                var content = (payment_type == 'online' ? '在线支付' : '货到付款');
            }

            $('#pay_name').val(payment_type);

            if (payment_type == 'offline'){
                //如果混合支付（在线+货到付款）
                if (hybrid) {
                    content = $('#show_goods_list').clone().html();
                    $('#edit_payment').parent().after('<div><ul><li>您选择货到付款 + 在线支付完成此订单<br/><a href="javsacript:void(0);" id="show_goods_list" class="payment-goods">'+content+'</a></li></ul></div>');
                    $('#show_goods_list').hover(function(){showPayGoodsList(this)},function(){$('#payment_goods_list').fadeOut()});
                } else {
                    $('#edit_payment').parent().after('<div><ul><li>'+content+'</li></ul></div>');
                }
            }else{
                $('#edit_payment').parent().after('<div><ul><li>'+content+'</li></ul></div>');
            }
            ableOtherEdit();
        });
        $('#show_goods_list').hover(function(){showPayGoodsList(this)},function(){$('#payment_goods_list').fadeOut()});
        function showPayGoodsList(item){
            var pos = $(item).position();
            var pos_x = pos.left+0;
            var pos_y = pos.top+25;
            $("#payment_goods_list").css({'left' : pos_x, 'top' : pos_y,'position' : 'absolute','display' : 'block'}).addClass('payment-goods-list').fadeIn();
        }
        $('input[name="payment_type"]').on('change',function(){
            if ($(this).val() == 'online'){
                $('#show_goods_list').hide();
            } else {
                if ($(this).val() == 'offline') {
                    setCodGoodsShow();
                    //判断该地区(县ID)是否能货到付款
                    if (($('#allow_offpay').val() == '0') || failInPage) {
                        $('#payment_type_online').attr('checked',true);
                        showDialog('您目前选择的收货地区不支持货到付款', 'error','','','','','','','','',2);return;
                    }
                    html_form('confirm_pay_type', '请确认支付方式', $('#confirm_offpay_goods_list').html(), 500,1);
                    $('#show_goods_list').show();
                } else {
                }
            }
        });

        $('body').on('click','#close_confirm_button',function(){
            DialogManager.close('confirm_pay_type');
        });
    })
</script>
<script type="text/javascript">
    //隐藏发票列表
    function hideInvList(content) {
        $('#edit_invoice').show();
        $("#invoice_list").html('<ul><li>' + content + '</li></ul>');
        $('.current_box').removeClass('current_box');
        ableOtherEdit();
        //重新定位到顶部
        $("html, body").animate({scrollTop: 0}, 0);
    }
    //加载发票列表
    $('#edit_invoice').on('click', function () {
        $(this).hide();
        disableOtherEdit();
        $(this).parent().parent().addClass('current_box');
        $('#invoice_list').load(SITEURL + '/index.php?act=buy&op=load_inv&vat_hash=<?php echo $output['vat_hash'];?>');
    });
</script>