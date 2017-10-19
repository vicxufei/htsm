/**
 * Created by yefeng on 16/4/23.
 */
/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var minibar = require('minibar');
  var header = require('header');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //fix顶部
    var jFilter = $("#jFilter");
    $(window).on('scroll',function(){
      fix();
    });
    var fix=function(){
      if($(window).scrollTop() >= jFilter.offset().top){
        jFilter.addClass('filter-fix');
      }else {
        jFilter.removeClass('filter-fix');
      }
    };

    $('[data-node-type="goods-cols"]').mouseover(function(){
      $(this).addClass('li-hover');
    }).mouseout(function(){
      $(this).removeClass('li-hover');
    });



  });
});

