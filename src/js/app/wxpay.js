/**
 * Created by yefeng on 16/4/23.
 */
/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var header = require('header');
  var layer = require('../lib/layer');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var wxPayment = $('.wxpayment');
    var payId = wxPayment.data('pay-id');
    var orderId = wxPayment.data('order-id');
    var endTimestamp = Date.parse(new Date()) + 1000*60;
     var interval = setInterval(queryOrderState, 3000);
    function queryOrderState(){
      var CurrentTimestamp = Date.parse(new Date());
      if (CurrentTimestamp > endTimestamp){
        clearInterval(interval);
      //  window.location.href = apiUrl + 'act=member_order';
      }
      $.ajax({
        type: "GET",
        url: "index.php?controller=payment&action=query_state",
        data: {
          pay_id : payId,
          order_id : orderId
        },
        dataType: "json",
        timeout: 4000,
        async:false,
        success: function(data) {
          if(data.code==200){
            layer.msg('支付成功');
            window.location.href = 'index.php?controller=member_order';
          }
        }
      });
    }


  }); //DOM ready
});

