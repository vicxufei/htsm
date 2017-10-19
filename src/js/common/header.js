/**
 * Created by yefeng on 16/4/7.
 */

require(['jquery'],function($){
  var shopUrl = 'http://www.htths.com/index.php?';

    //顶部的下拉菜单
    $(".jMenu").mousemove(function(){
      $(this).addClass("menu-hover");
      $(this).children("div .menu-bd").show();
    });
    $(".jMenu").mouseout(function(){
      $(this).removeClass("menu-hover");
      $(this).children("div .menu-bd").hide();
    });

    //点击搜索框显示关键词历史
    $("#jSearch input").focusin(function(){
      $(".k-history").show();
    });
    $("#jSearch input").focusout(function(){
      $(".k-history").hide();
    });

  $('.jBtnAllSearch').click(function(){
    search($("input[name='keyword']").val());
  });


  $('.kh-item').mousedown(function(){
    search($(this).data('key'));
  });

  function search(keyword){
    window.location.href = shopUrl + 'controller=search&action=index&keyword=' + keyword;
  }

    //在弹出的搜索历史中,各条目移上去改变颜色
    $("#jSearchHistoryBox .kh-item").mousemove(function(){
      $(this).addClass("kh-hover");
    });
    $("#jSearchHistoryBox .kh-item").mouseout(function(){
      $(this).removeClass("kh-hover");
    });

    //导航菜单
    $(".jNavTop").mousemove(function(){
      $(this).addClass("nav-hover");
      $(this).children("div .jPinDown").show();
    });
    $(".jNavTop").mouseout(function(){
      $(this).removeClass("nav-hover");
      $(this).children("div .jPinDown").hide();
    });






});