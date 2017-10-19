/**
 * Created by yefeng on 16/4/9.
 */
define(function (require) {
  var $ = require('jquery');
  var scrollto = require('scrollto');
  var template = require('template');
  $(function () {
    var shopUrl = 'http://www.htths.com/index.php?';
    //显示隐藏返回顶部按钮
    checkPositon($(window).height());
    $(window).on('scroll',function(){
      checkPositon($(window).height());
    });
    function checkPositon(pos){
      if ($(window).scrollTop() > pos){
        $('.jScrollToTop').fadeIn();
      }else{
        $('.jScrollToTop').fadeOut();
      }
    }

    //返回顶部
    var scroll = new scrollto.ScrollTo({
      dest: 0,
      speed:800
    });
    $('.jScrollToTop').on('click', $.proxy(scroll.move, scroll));

    //获取购物车列表
    var getCartList = function(){
      $.ajax({
        url: shopUrl + 'controller=cart&action=ajax_load',
        success: function(result){
          $('.jCartCount').text(result.cart_goods_num);
          $('.jTotalPrice').text(result.cart_all_price);
          //console.log(result);
          if(result.list){
            $('.jGoCart').removeClass('btn-disabled');
            var html = template('tplCartList', result);
            $('.cart-cnt').html(html);

            //$("a.jOpDel").click(function(){
            //      console.log("sds");
            //});

          }else {
            $('.jGoCart').addClass('btn-disabled');
          }
        },
        dataType: "json"
      });
    };
    getCartList();

    //打开关闭minibar
    var minibarFlag=0;
    $("#jMinCart").click(function(){
      getCartList();
      if(minibarFlag ==1){
        $(".rich-main").css("right","0px");
        minibarFlag=0;
      }else {
        $(".rich-main").css("right","275px");
        $(".rich-panel-box").removeClass("box-hide");
        $(".rich-panel-box").addClass("box-show");
        minibarFlag=1;
      }
    });


    //购物车删除
    $(document).ready(function() {
      //$("a.jOpDel").click(function(){
      //      console.log("sds");
      //});
      $(".cart-cnt").on('click','a',function(){
        var cartId = $(this).data("cart-id");
        var obj = $(this);
        $.getJSON(shopUrl + 'controller=cart&action=del&cart_id='+cartId+'&callback=?', function(result){
          //删除成功
          if(result.datas.cart_count == 0){
            html='<li class="cart-empty">^_^&nbsp;&nbsp;既来之，则买之！</li>';
            $('.cart-cnt').html(html);
            $('.jTotalPrice').text(0.00);
            $('.jCartCount').text(0);
            $('.jGoCart').addClass('btn-disabled');
          }else{
            obj.parents("li").remove();
            $('.jTotalPrice').text(result.datas.cart_total);
            $('.jCartCount').text(result.datas.cart_count);
            if ($('.cart-cnt').children().size() != result.datas.cart_count) {
              getCartList();
            }
          }

        });





      });


    });


    function delCartItem(cart_id){

    }
  });  //DOM ready
});
