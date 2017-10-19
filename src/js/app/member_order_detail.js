/**
 * Created by yefeng on 16/4/23.
 */
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
    $("#get_express").click(function(){
      var eCode = $(this).data("ecode");
      var shippingCode =  $(this).data("shipping-code");
      $.getJSON(shopUrl,{
        'act':'member_order',
        'op':'get_express',
        'e_code':eCode,
        shipping_code:shippingCode
      },function(data){
        console.log(data);
        var html = '';
        for(shoppingItem in data){
          html += data[shoppingItem] + "<br />"
        }
       // layer.tips(html);
        $("#express-detail").html(html);
      });
    });



  }); //DOM ready
});

