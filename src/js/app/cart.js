/**
 * Created by yefeng on 16/4/23.
 */
/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var template = require('template');
  var layer = require('../lib/layer');
  var header = require('header');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var shopUrl = 'http://www.htths.com/index.php?';
    var jCart = $('#jCart');
    //进入购物车页面载入列表
    var renderHtml = function(result){
      if(result.datas.cart_list){
        var html = template('tpl-jCart', result.datas);
      }else{
        var html = '<div class="empty clearfix">\
                      <div class="f-l empty-img"><i class="icon iconfont"></i></div>\
                      <div class="f-l empty-cnt">\
                        <h2>空荡荡的什么也没有...</h2>\
                          <a href="<?php echo SHOP_SITE_URL; ?>" class="more">随便逛逛，或许有意外惊喜&gt;&gt;</a>\
                      </div>\
                    </div>';
      }
      jCart.html(html);
      //滚动
      //checkPosition();
      //var jSetBox = $(jCart).find('#jSetBox');
      //$(window).scroll(function(event){
      //  checkPosition();
      //});
      //function checkPosition(){
      //  if($(jSetBox).offset().top() < (screen.height - $(window).scrollTop()) ){
      //    $(jSetBox).addClass('settlement-float');
      //  }else{
      //    $(jSetBox).removeClass('settlement-float');
      //  }
      //}

    };
    $.getJSON(shopUrl + 'controller=cart&action=cart_list', function(result){
      renderHtml(result);
    });

    var checkItem = function(cartId,ifChecked){
      var index = layer.load(0, {shade: false});
      $.ajax({
        type : 'post',
        url: shopUrl + 'controller=cart&action=cart_checked',
        data: {
          cart_id:cartId,
          cart_checked:ifChecked
          //if_chain: ifChain
        },
        success: function(result){
          layer.close(index);
          renderHtml(result);
        },
        dataType: "json"
      });

    };
    //勾选,去勾选
    jCart.on('click','.jChkItem',function(){
      var _this = $(this);
      var jSubmit = $('.jSubmit');
      var ifChecked =_this.attr('checked') === 'checked' ? 0 : 1;  //尝试的操作  0:取消勾选  1:勾选
      var hasBondedChecked = jSubmit.data('bonded-checked');
      var hasNonBondedChecked = jSubmit.data('non-bonded-checked');
      var ifBonded = _this.data('if-bonded');  //尝试操作的项目是  0:非保税仓的   1:保税仓的
      //如果要勾选非保税仓的商品,先检查有没有保税仓的商品已经被勾选
      if(ifChecked && ifBonded == 0 && hasBondedChecked){
        layer.alert('请先取消保税仓商品的勾选,或先完成保税仓商品的订单!', {icon: 6});
        _this.prop('checked', false);
      }else if(ifChecked && ifBonded == 1 && hasNonBondedChecked){
        //如果要勾选保税仓的商品,先检查有没有非保税仓的商品已经被勾选
        layer.alert('请先取消现货商品的勾选,或先完成现货商品的订单!', {icon: 6});
        _this.prop('checked', false);
      }else{
        var cartId = _this.parents('table').data('cart-id');
        checkItem(cartId,ifChecked);
      }
    });
    //全选操作
    jCart.on('click','.jChkAll',function(){
      var _this = $(this);
      var jSubmit = $('.jSubmit');
      var ifChecked =_this.attr('checked') === 'checked' ? 0 : 1;  //尝试的操作  0:取消勾选  1:勾选
      var bothBondedNone = jSubmit.data('both-bonded-none');
      //如果尝试勾选全部,但购物车列表中同时包含现货商品和保税仓商品,则提示
      if(ifChecked && bothBondedNone){
        layer.alert('购物车列表中包含的保税仓及非保税仓的商品,不能同时下单!', {icon: 6});
        _this.prop('checked', false);
      }else{
        var cartIds = _this.data('cart-all');
        checkItem(cartIds,ifChecked);
      }
    });
    //选择上门自提或快递配送的选择
    jCart.on('change',"input:radio[name='ifchain']",function(){
      var cartTotal = $(".jCartTotal").data("cart-total");
      var jNeed2Pay = $(".jNeed2Pay");
      if($(this).val() == 1){
         var need2Pay = '￥' + cartTotal;
      }else{
        var need2Pay= jNeed2Pay.data("need-pay");
      }
      jNeed2Pay.text(need2Pay);
    });

    /**
     * 更改购物车数量
     * @param cart_id
     * @param input
     */
    var change_quantity = function(cart_id, jQty,_this){
      //暂存为局部变量，否则如果用户输入过快有可能造成前后值不一致的问题
      if(jQty <= 0){
        layer.tips('再减就没啦!',_this,{tips: 3});
      }else {
        var index = layer.load(0, {shade: false});
        var ifChain = $("#ifchain").val();
        $.getJSON(shopUrl + 'controller=cart&action=update',{
          cart_id : cart_id,
          quantity : jQty,
          if_chain : ifChain
        }, function(data){
          layer.close(index);
          var html = template('tpl-jCart', data);
          jCart.html(html);
        });
      } //if end

    };
    //更新购物车
    jCart.on('click','.jQtyAdd',function(){
      var _this = $(this);
      var cartId = $(this).parents('table').data('cart-id');
      jQty = parseInt(_this.prev().val()) +1;
      change_quantity(cartId,jQty,_this);
    });
    jCart.on('click','.jQtyMin',function(){
      var _this = $(this);
      var cartId = _this.parents('table').data('cart-id');
      jQty = parseInt(_this.next().val()) -1;
      change_quantity(cartId,jQty,_this);
    });
    jCart.on('blur','.jQtyTxt',function(){
      var _this = $(this);
      var cartId = _this.parents('table').data('cart-id');
      jQty= parseInt(_this.val());
      change_quantity(cartId,jQty,_this);
    });


    //提交结算
    jCart.on('click','.jSubmit',function(){
      $('form').submit();
    });
    //end


    jCart.on('click','.jDel',function(e){
      var ex=e || event;
      var cartId = $(this).parents('table').data('cart-id');
      var offsetTop = ex.clientY + 'px';
      var offsetLeft = (ex.clientX - 200) + 'px';
      layer.msg('确定删除吗,删除后不可以恢复哦？', {
        time: 0 //不自动关闭
        ,btn: ['确定', '取消']
        ,offset: [offsetTop,offsetLeft]
        ,yes: function(index){
          $.ajax({
            type : 'get',
            url: shopUrl + 'controller=cart&action=del',
            data: {
              cart_id:cartId
            },
            success: function(result){
              layer.close(index);
              renderHtml(result);
            },
            dataType: "json"
          });


        }
      });

    });


  });
});

