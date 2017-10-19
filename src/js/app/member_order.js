/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var layer = require('../lib/layer');
  var header = require('header');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var shopUrl = 'http://www.htths.com/index.php?';
    $('.jOrderOp').click(function(e){
      var _this = $(this);
      var currentUrl = window.location.href;
      var orderId = _this.data('order-id');
      var orderOp = _this.data('op-type');
      switch(orderOp)
      {
        case 'order_receive':
          var confirmMsg = '确认收货?';
          var orderSn = _this.data('order-sn');
          break;
        case 'order_cancel':
          var confirmMsg = '取消订单?';
          break;
        case 'order_delete':
          var confirmMsg = '您确定要删除吗?删除后该订单可以在回收站找回，或彻底删除';
          break;
        default:
          var confirmMsg = '你确定要这样做吗?';
      }


      var ex=e || event;
      var offsetTop = ex.clientY + 'px';
      var offsetLeft = (ex.clientX - 200) + 'px';
      layer.msg(confirmMsg, {
        time: 0 //不自动关闭
        ,btn: ['确定', '取消']
        ,offset: [offsetTop,offsetLeft]
        ,yes: function(index){
          $.ajax({
            type : 'get',
            url: shopUrl + 'controller=member_order&action=change_state',
            data: {
              state_type: orderOp,
              order_id : orderId,
              order_sn : orderSn
            },
            success: function(data){
              layer.close(index);
              if(data.code== 200){
                layer.msg(data.datas);
                window.location.href = currentUrl;
              }
              if(data.code== 400){
                layer.msg(data.datas.error);
              }
            },
            dataType: "json"
          });


        }
      });

    });




  }); //DOM ready
});

