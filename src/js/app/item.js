/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var template = require('template');
  var jscookie = require('jscookie');
  var scrollto = require('scrollto');
  var minibar = require('minibar');
  var header = require('header');
  var layer = require('../lib/layer');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var shopUrl = 'http://www.htths.com/index.php?';
    var memberUrl = 'http://i.htths.com/index.php?';
    var apiUrl = 'http://api.htths.com/index.php?';
    var goodId= $("#jGoodsH1").attr("data-goodid");

    var checkLogin = function(){
      var isLogin = $("#is-login li").data("is-login");
      return isLogin;
    };

    //幻灯片-放大镜
    var picBig=$('.pic-big');
    var picMid=$('.pic-mid');
    var prevDrapBox = $('.prev-drag-box');
    var prevDrag=$('.prev-drag');
    picMid.mouseover(function(){
      prevDrag.css('display','block');
      picBig.css('display','block');
    });
    picMid.mouseout(function(){
      picBig.css('display','none');
      prevDrag.css('display','none');
    });
    prevDrapBox.mousemove(function(event){
      var left = event.pageX - prevDrag.width()/2;
      var top = event.pageY - prevDrag.height()/2;
      if(left < prevDrapBox.offset().left){
        left = prevDrapBox.offset().left;
      }else if(left + prevDrag.width() > prevDrapBox.offset().left + prevDrapBox.width()){
        left = prevDrapBox.offset().left + prevDrapBox.width() - prevDrag.width();
      }
      if(top < prevDrapBox.offset().top){
        top = prevDrapBox.offset().top;
      }else if(top + prevDrag.height() > prevDrapBox.offset().top + prevDrapBox.height()){
        top = prevDrapBox.offset().top + prevDrapBox.height() - prevDrag.height();
      }
      prevDrag.offset({top:top,left:left});

      var percentX = (left - prevDrapBox.offset().left )/ ( prevDrapBox.width()- prevDrag.width() );
      var percentY = (top - prevDrapBox.offset().top ) / ( prevDrapBox.height() - prevDrag.height() );

      var bigleft = -percentX * 400;
      var bigtop = -percentY * 400;
      $('.pic-big img').css({left:bigleft,top:bigtop});
    });
    //幻灯片-切换图片
    $(".pic-list a").click(function(){
      $(this).siblings().removeClass("current");
      $(this).addClass("current");
      $('.pic-big img').attr("src",$(this).attr("data-pic-big"));
      $('.pic-mid img').attr("src",$(this).attr("data-pic-mid"));
    });

    //商品详情,评论fix
    $(window).on('scroll',function(){
      fix();
    });
    var fix=function(){
      if($(window).scrollTop() >= $("#jCutmain").offset().top){
        $(".jAnchorNav").css({
          position: "fixed",
          top: "0px",
          "z-index": 15
        }).find(".buys-info").show();
      }else {
        $(".jAnchorNav").removeAttr("style").find(".buys-info").hide();
      }
      if($(window).scrollTop() >= $("#gd-msg").offset().top){
        $('[data-type="gd-msg"]').siblings().removeClass('hover');
        $('[data-type="gd-msg"]').addClass('hover');
      }
      if($(window).scrollTop() >= $("#gd-details").offset().top){
        $('[data-type="gd-details"]').siblings().removeClass('hover');
        $('[data-type="gd-details"]').addClass('hover');
      }
      if($(window).scrollTop() >= $("#gd-comment").offset().top){
        $('[data-type="gd-comment"]').siblings().removeClass('hover');
        $('[data-type="gd-comment"]').addClass('hover');
      }

    };
    //商品详情,评论跳转
    $('#jNavTabTitle').find('li.jTabTitle').each(function(){
        $(this).on('click', function(){
          $(this).siblings().removeClass('hover');
          $(this).addClass('hover');
          var destElementID= '#' + $(this).data('type');
          var destElement = $(destElementID);
          var scroll = new scrollto.ScrollTo({
            dest: destElement.offset().top,
            speed:800
          });
          scroll.move();
        })
      }
    );


    //猜你喜欢
    var gcid= $(".jFreshRec").attr("data-gcid");
    var getRandList = function(){
      var index = layer.load(0, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
      });
      $.ajax({
        url: apiUrl + 'controller=goods&action=goods_rand_list',
        data: {
          goods_id:goodId,
          gc_id_1:gcid
        },
        success: function(result){
          var html = template('tplUlike', result);
          $('#jUmlikeList').html(html);
          layer.close(index);
        },
        dataType: "json"
      });
    };
    getRandList();
    $(".jFreshRec").click(function(){
      getRandList();
    });

    //记录浏览历史
    $.get(shopUrl + "controller=goods&action=addbrowse&gid=" + goodId);

    //数量加减
    var jQtyTxt = $('.jQtyTxt');
    var goodStorage = parseInt(jQtyTxt.attr('data-good-storage'));
    var upperLimit = parseInt(jQtyTxt.attr('data-upper-limit'));
    function checkQty(that){
      if( jQty > goodStorage){
        jQtyTxt.val(goodStorage);
        layer.tips('库存不足',that,{tips: 2});
      }else if(jQty > upperLimit) {
        jQtyTxt.val(upperLimit);
        layer.tips('当前限购' + upperLimit +'件',that,{tips: 2});
      } else{
        jQtyTxt.val(jQty);
        return false
      }
    }
    $('.jQtyAdd').on('click',function(){
      jQty= parseInt(jQtyTxt.val()) + 1;
      var that = this;
      checkQty(that);
    });
    $('.jQtyMin').on('click',function(){
      if(jQtyTxt.val()>0){
        jQty= parseInt(jQtyTxt.val()) - 1;
      }else {
        jQtyTxt.val(0);
      }
      var that = this;
      checkQty(that);
    });
    jQtyTxt.blur(function(){
      jQty= parseInt(jQtyTxt.val());
      var that = this;
      checkQty(that);
    });

    //立即购买
    $('.jFastBuy').click(function(){
      if(!checkLogin()){
        window.location.href = "http://i.htths.com/index.php?controller=login&action=index";
      }else{
        $('input#cart_id').val(goodId + '|' +jQtyTxt.val());
        if($(this).data('ifchain') == 1){
          var index = layer.confirm('选择快递配送还是自提？', {
            btn: ['上门自提','快递配送','再想想'] ,closeBtn: 0
          },function(){
            $('#ifchain').val(1);
            $('#buynow_form').submit();
          },function(){
            $('#ifchain').val(0);
            $('#buynow_form').submit();
          }, function(index){
            layer.close(index);
          });
        }else {
          $('#buynow_form').submit();
        }
      }
    });

    //加入购物车
    var jMinCartDest = $("#jMinCart").offset();
    $(".jAddCart").click(function(e){
      if(!checkLogin()){
        window.location.href = "http://i.htths.com/index.php?controller=login&action=index";
      }else{
        //      $(this).addClass('btn-disabled');
        var ex=e || event;
        //postData
        $.ajax({
          url: shopUrl + 'controller=cart&action=add',
          data: {
            goods_id:goodId,
            quantity:jQtyTxt.val()
          },
          success: function(result){
            if(result.code == 200){
              var img = $(".pic-list a.current img").clone();
              $(img).appendTo(".goods-btns").css({"position":"fixed","top":ex.clientY,"left":ex.clientX}).animate({
                left : jMinCartDest.left,
                top : jMinCartDest.top
              },"slow",function(){
                $(img).remove();
              });
              $(".jCartCount").text(result.datas.num);
            }else{
              layer.alert(result.msg);
            }
//          $(".jAddCart").removeClass('btn-disabled');
          },
          dataType: "json"
        });
      }
    });
    //将商品从minibar购物车中删除

    //规格选择
    var ObjAttrValue = $('.attr-value');
    ObjAttrValue.find('a').each(function(){
      $(this).click(function () {
        if ($(this).hasClass('cur')) {
          return false;
        }
        $(this).parents('div .attr-value').find('a').removeClass('cur');
        $(this).addClass('cur');
        checkSpec();
      });

    });
    function checkSpec() {
      var spec_param = $('#spec-list').data('spec-list');
      var spec = new Array();
      ObjAttrValue.find('a.cur').each(function () {
        var data_str = '';
        eval('data_str =' + $(this).data('attr-value'));
        spec.push(data_str);
      });
      spec1 = spec.sort(function (a, b) {
        return a - b;
      });
      var spec_sign = spec1.join('|');
      $.each(spec_param, function (index, element) {
        if (element.sign == spec_sign) {
          window.location.href = element.url;
        }
      });
    }

    //配送区域选择

    var $cur_area_list,$cur_tab,next_tab_id = 0;
    $("#freight-selector div.text").hover(function() {
      //如果店铺没有设置默认显示区域，马上异步请求
      if (typeof nc_a === "undefined") {
        $.getJSON(shopUrl + "controller=index&action=json_area&callback=?", function(data) {
          nc_a = data;
          $cur_tab = $('#ncs-stock').find('li[data-index="0"]');
          _loadArea(0);
        });
      }
      $("#freight-selector").find("div.content").show();
      //$(this).on('mouseleave',function(){
      //  $(this).find("div.content").hide();
      //});
    });

    $('ul[class="area-list"]').on('click','a',function(){
      $('#freight-selector').unbind('mouseleave');
      var tab_id = parseInt($(this).parents('div[data-widget="tab-content"]:first').attr('data-area'));
      if (tab_id == 0) {cur_select_area = [];cur_select_area_ids = []};
      if (tab_id == 1 && cur_select_area.length > 1) {
        cur_select_area.pop();
        cur_select_area_ids.pop();
        if (cur_select_area.length > 1) {
          cur_select_area.pop();
          cur_select_area_ids.pop();
        }
      }
      next_tab_id = tab_id + 1;
      var area_id = $(this).attr('data-value');
      $cur_tab = $('#ncs-stock').find('li[data-index="'+tab_id+'"]');
      $cur_tab.find('em').html($(this).html());
      $cur_tab.find('i').html(' ∨');
      if (tab_id < 2) {
        calc_area_id = area_id;
        cur_select_area.push($(this).html());
        cur_select_area_ids.push(area_id);
        $cur_tab.find('a').removeClass('hover');
        $cur_tab.nextAll().remove();
        if (typeof nc_a === "undefined") {
          $.getJSON("http://www.htths.com/index.php?controller=index&action=json_area&callback=?", function(data) {
            nc_a = data;
            _loadArea(area_id);
          });
        } else {
          _loadArea(area_id);
        }
      } else {
        //点击第三级，不需要显示子分类
        calc_area_id = area_id;
        if (cur_select_area.length == 3) {
          cur_select_area.pop();
          cur_select_area_ids.pop();
        }
        cur_select_area.push($(this).html());
        cur_select_area_ids.push(area_id);
        $('#freight-selector > div[class="text"] > div').html(cur_select_area.join(''));
        $("#freight-selector").find("div.content").hide();
        _calc();
      }


      $('#ncs-stock').find('li[data-widget="tab-item"]').click(function(){
        var tab_id = parseInt($(this).attr('data-index'));
        if (tab_id < 2) {
          $(this).nextAll().remove();
          $(this).addClass('hover');
          $('#ncs-stock').find('div[data-widget="tab-content"]').each(function(){
            if ($(this).attr("data-area") == tab_id) {
              $(this).show();
            } else {
              $(this).hide();
            }
          });
        }

      });
    });
    function _loadArea(area_id){
      if (nc_a[area_id] && nc_a[area_id].length > 0) {
        $('#ncs-stock').find('div[data-widget="tab-content"]').each(function(){
          if ($(this).attr("data-area") == next_tab_id) {
            $(this).show();
            $cur_area_list = $(this).find('ul');
            $cur_area_list.html('');
          } else {
            $(this).hide();
          }
        });
        var areas = [];
        areas = nc_a[area_id];
        for (i = 0; i < areas.length; i++) {
          if (areas[i][1].length > 8) {
            $cur_area_list.append("<li class='longer-area'><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
          } else {
            $cur_area_list.append("<li><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
          }
        }
        if (area_id > 0){
          $cur_tab.after('<li data-index="' + (next_tab_id) + '" data-widget="tab-item"><a class="hover" href="#none" ><em>请选择</em><i> ∨</i></a></li>');
        }
      } else {
        //点击第一二级时，已经到了最后一级
        $cur_tab.find('a').addClass('hover');
        $('#freight-selector > div[class="text"] > div').html(cur_select_area);
        $("#freight-selector").find("div.content").hide();
        _calc();
      }
    }

    //计算运费，是否配送
    function _calc() {
      var transportId = $("#jGoodsH1").data("transport-id");
      //console.log(cur_select_area_ids);
      jscookie.set('dregion', cur_select_area_ids.join(' ')+'|'+cur_select_area.join(' '), { expires: 30 });
      // console.log(jscookie.get('dregion'));
      if (typeof cur_select_area_ids[2] != 'undefined') {
        //需要请求配送区域设置
        $.getJSON(shopUrl +  "controller=goods&action=area_freight",{tid:transportId,area_id:cur_select_area_ids[2]}, function(data){
          //console.log(data);
          var html = '';
          if(data.datas.supported){
            html = '配送区域:' + data.datas.transport_title + ',配送费:' + data.datas.sprice +'元';
            if(data.datas.baoyou > 0){
              html += ',满' + data.datas.baoyou + '包邮!';
            }
            $("#jExpress").html(html);
            $(".jAddCart").removeClass('btn-disabled');
            $(".jFastBuy").removeClass('btn-disabled');
          }else {
            html = '支持的配送区域为:' + data.datas.transport_title;
            $("#jExpress").html(html);
            $(".jAddCart").addClass('btn-disabled');
            $(".jFastBuy").addClass('btn-disabled');
          }

        });
      } else {
        layer.alert('请正确选择配送区域');
      }
    }


    //收藏商品
    $('.jColGoods').click(function(){
      var that = this;
      $.getJSON( shopUrl + "controller=member_favorite_goods&action=favoritegoods",{fid:goodId}, function(data){
        //console.log(data);

        if(data.code == 400){
          layer.tips(data.datas.error,that,{tips: 3});
        }
        if(data.code == 200){
          layer.tips(data.datas,that,{tips: 3});
        }

      });
    });
    //end


  });
});
