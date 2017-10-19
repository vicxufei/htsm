/**
 * Created by yefeng on 16/4/23.
 */
/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var layer = require('../lib/layer');
  var countdown = require('countdown');
  var header = require('header');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    //倒计时
    var jCountTime = $('#jCountTime');
    var endTime = jCountTime.data('endtime') * 1000 + 3600000;
    jCountTime.countdown(endTime, function(event) {
      $(this).html(
        event.strftime('<em>%D</em><em>天</em><em>%H</em><em>时</em><em>%M</em><em>分</em><em>%S</em><em>秒</em>')
      );
    });


    //切换支付方式
    var jPayType = $('#jPayType');
    $('.jCardItem').click(function(){
      var _this = $(this);
      jPayType.find("input[type='radio'][name='payment_code']").prop('checked', false);
      jPayType.find('.jCardItem').removeClass('hover');
      _this.addClass('hover');
      _this.find('input:radio').prop('checked', true);
    });

    //提交发起支付
    $('.jSubMit').click(function(){
      if(!$("input[type='radio'][name='payment_code']:checked").val()){
        layer.msg('请选择支付方式');
      }else{
        console.log($('#pay_form').serialize());
        $('#jPayForm').submit()
      }


    });






  }); //DOM ready
});

