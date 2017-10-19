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
  var minibar = require('minibar');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    //首页幻灯
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var yf_slide= function(nextSlideIdx,direction){
      var slideNum = $('div.slide-num');
      var nowSlide = slideNum.find('a.hover');
      var nowSlideIdx = nowSlide.data('slide-idx');

      var slideItems = $("#jSlide li.slide-item");
      var maxSlideItemsIdx = slideItems.length -1;
      if(nextSlideIdx == ''){
        if(direction === 0){
          nextSlideIdx = nowSlideIdx > 0 ? nowSlideIdx - 1 : maxSlideItemsIdx;
        }else{
          nextSlideIdx = nowSlideIdx < maxSlideItemsIdx ?  nowSlideIdx + 1 : 0;
        }
      }

      nowSlide.removeClass("hover");
      slideItems.eq(nowSlideIdx).animate({opacity:0,"z-index": 1},1e3);
      slideNum.find("a").eq(nextSlideIdx).addClass("hover");
      slideItems.eq(nextSlideIdx).animate({opacity:1,"z-index": 9},1e3);
    };

    var autoRun = setInterval(function(){
      yf_slide('');
    },2000);

    $('#jSlide').mouseover(function(){
      $(this).children('a').show();
      clearInterval(autoRun);
    }).mouseout(function(){
      $(this).children('a').hide();
       autoRun = setInterval(function(){
        yf_slide('');
      },2000);
    });

    $('div.slide-num a').click(function(){
      var nextSlideIdx = $(this).data('slide-idx');
      yf_slide(nextSlideIdx);
      return false;
    });

    $('.jImgLeft').click(function(){
      yf_slide('',0);
    });

    $('.jImgRight').click(function(){
      yf_slide('');
    });


    //新品上线
    var slide0 = 0;
    var slide1 = -1196;
    var slide2 = -2392;
    $('li.ui-slider-pagination-item a').click(function(){
      var slideIdx = $(this).data('slide-idx');
      $('.ui-slider-pagination').find('.ui-slider-pagination-item').removeClass('hover');
      $(this).parent().addClass('hover');
      $('.slider-item').animate({'left':eval('slide' + slideIdx)},'slow');
      return false;
    });

    var activeNewSlide = function(direction){
      var hoverSlide = $('ul.ui-slider-pagination').find('li.hover');
      var slideIdx = hoverSlide.children().data('slide-idx');
      var direction = 'next' || direction;
      if(direction == 'next'){
        slideIdx < 2 ? ++slideIdx : slideIdx = 0;
      }else if(direction == 'prev'){
        slideIdx > 0 ? --slideIdx : slideIdx = 2;
      }
      $('.ui-slider-pagination').find('.ui-slider-pagination-item').removeClass('hover');
      var newSlide =$('.ui-slider-pagination-item')[slideIdx];
      $(newSlide).addClass('hover');
      $('.slider-item').animate({'left':eval('slide' + slideIdx)},'slow');
      return false;
    };

    $('.ui-slider-prev').click(function(){
      activeNewSlide('prev');
      return false;
    });

    $('.ui-slider-next').click(function(){
      activeNewSlide('next');
      return false;
    });



  }); //DOM ready
});

