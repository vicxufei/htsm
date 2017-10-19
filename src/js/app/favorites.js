/**
 * Created by yefeng on 16/5/4.
 */

define(function (require) {
  var $ = require('jquery');
  var header = require('header');
  var layer = require('../lib/layer');


  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var shopUrl = 'http://www.htths.com/index.php?';
    //取消商品收藏
    $('.jCancelF').click(function(e){
      var _this = $(this);
      var currentUrl = window.location.href;
      var favId = _this.data('fav-id');
      var ex=e || event;
      var offsetTop = ex.clientY + 'px';
      var offsetLeft = (ex.clientX - 200) + 'px';
      layer.msg('确定取消收藏?', {
        time: 0 //不自动关闭
        ,btn: ['确定', '取消']
        ,offset: [offsetTop,offsetLeft]
        ,yes: function(index){
          $.ajax({
            type : 'get',
            url: shopUrl + 'controller=member_favorite_goods&action=delfavorites',
            data: {
              type: 'goods',
              fav_id : favId
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
        } //yes
      }); //layerMsg
    });//click
  });//DOM ready
});
