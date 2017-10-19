/**
 * Created by yefeng on 16/8/25.
 */
$(document).ready(function () {
  // 配置项说明:https://www.uedsc.com/fullpage.html
  $('#fullpage').fullpage({
    //sectionsColor:['green','orange','gra y','red'],

    //定义浏览器地址栏中的锚链接,锚链接的值不要和页面中任意的ID或name相同
    anchors:['section1','section2','section3','section4','section5'],
    // 滚动时,页面上的锚链接不发生改变,默认为false
    // lockAnchors: false,
    // css3: true
    // loopBottom:true,
    menu: '#menu',
    // fixedElements : '.fixedBar',
    navigation:true,
    navigationTooltips: ['首页','幻灯','商品','商品','联系我们'],
    afterLoad: function (link,index) {
      if(index>1 && index < 5){
        $('.nav-goods').addClass('active');
      }else{
        $('.nav-goods').removeClass('active');
      }

      // switch (index){
      //   case 1:
      //     // move('.section1 h1').scale(0.9).end();
      //     break;
      //   case 2:
      //     break;
      //   case 3:
      //     move('.section3 h1').scale(0.9).end();
      //     // move('.section3 h1').set('margin-left','0').end();
      //     // move('.section3 p').set('margin-left','0').end();
      //     break;
      //   case 4:
      //     move('.section4 h1').scale(0.9).end();
      //     // move('.section4 h1').set('margin-left','0').end();
      //     // move('.section4 p').set('margin-left','0').end();
      //     break;
      //   default :
      //     break
      // }
    },
    onLeave: function (link,index) {
      // switch (index){
      //   case 1:
      //     break;
      //   case 2:
      //     break;
      //   case 3:
      //     move('.section3 h1').scale(1).end();
      //     // move('.section3 h1').set('margin-left','-200%').end();
      //     // move('.section3 p').set('margin-left','200%').end();
      //     break;
      //   case 4:
      //     move('.section4 h1').scale(1).end();
      //     // move('.section4 h1').set('margin-left','-200%').end();
      //     // move('.section4 p').set('margin-left','200%').end();
      //     break;
      //   default :
      //     break
      // }
    },
    afterRender: function (link,index) {

      setInterval(function () {
        $.fn.fullpage.moveSlideRight();
      }, 3000);
      // $('video').get(0).play();
      switch (index){
        case 1:
          break;
        case 2:
          break;
        case 3:
          break;
        case 4:
          break;
        default :
          break
      }
    }
  });

  });