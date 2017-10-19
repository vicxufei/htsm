$(function () {
  'use strict';

  $(".kefu").off('click').click(function () {
    _MEIQIA('showPanel');
  });

  var goodsParm = store.get('goodsParm');
  if(typeof goodsParm == 'undefined') {
    goodsParm = {
      //gc_id : '',
      curpage : 1,
      page : 8,
      key: 4,
      order: 1
      //keyword : ''
    };
    store.set('goodsParm',goodsParm);
  }
  //执行搜索
  $(".search").off('keypress').keypress(function(e) {
    if(e.which == 13) {
      goodsParm.gc_id = '';
      goodsParm.keyword = $('.search').val();
      store.set('goodsParm',goodsParm);
      document.activeElement.blur();
      $.router.load('#goods');
    }
  });

  //获取商品的ID
  var indexOfIdS = location.href.indexOf('goods_id=');
  // if(indexOfIdS != -1){
  //   var indexOfidE = location.href.indexOf('share');
  //   session.set('good_id',location.href.slice(indexOfIdS + 9,indexOfidE));
  // }
  if(indexOfIdS != -1){
    session.set('good_id',location.href.slice(indexOfIdS + 9));
  }

  var bLazy = new Blazy();
  var _cartnum = store.get('cartnum');
  if(_cartnum > 0){
    $('.cart-num').text(_cartnum);
  }


  //跳转到商品页面
  $(document).on('click','a.jGo',function () {
    var $this = $(this);
    var type= $this.data('type');
    if(type){
      var parm = $this.data('parm');
      var goodsParm = store.get('goodsParm');
      switch (type){
        case 'catlog':
          goodsParm.gc_id = parm;
          goodsParm.keyword = '';
          break;
        case 'keyword':
          goodsParm.gc_id = '';
          goodsParm.keyword = parm;
      }
      store.set('goodsParm', goodsParm);
      $.router.load('#goods', 'true');
    }else{
      session.set('good_id',$(this).data("good_id"));
      $.router.load('#item', 'true');
    }
  });

  /**
   * home首页
   */
  $(document).on("pageInit", "#home", function (e, pageId, page) {
    ajax('GET','index','xianshi2','',function(ret){
      renderHtml($('#xianshi'),'xianshi', ret.datas);
      $('.countdown').each(function () {
        var time = $(this).data('endtime');
        if(time){
          new Countdown(this,{
            format: "hh小时mm分ss秒",
            lastTime: time
          });
        }

      });

    });



    //lazyLoad
    $(page).find(".content").on("scroll", function () {
      bLazy.revalidate();
    });


    getList('hot_list','{page:5,key:1,curpage:1}');
    getList('recommend_list','{page:6,key:2,curpage:1}');

    function getList(listName,parm) {
      var list = store.get(listName);
      var $wapper = $('#' +listName);
      if(list){
        renderHtml($wapper,'goods', list);
      }else{
        ajax('GET','goods','goods_list206',parm,function(ret){
          var list = ret.datas;
          store.set(listName,list,64800);
          renderHtml($wapper,'goods', list);
        });
      }
    }

    //无限加载
    var curpage = 1;
    var requestMore = true;
    $(page).on('infinite', function () {
      if(!requestMore) {return false}
      requestMore = false;
      curpage++;
      update_new_list(curpage);
      function update_new_list(current_page){
        ajax('get','goods','goods_list206',{page:8,key:4,curpage:current_page},function(ret){
          renderHtml($("#new-list"),'goods', ret.datas,true);
          $.refreshScroller();
          if (ret.hasmore){
            requestMore = true;
          }
        },function () {
          curpage--;
          requestMore = true;
        });
      }
    });
  });

  /**
   * catlog分类
   */
  $(document).on("pageInit", "#catlog", function (e, pageId, $page) {
    var fromCache = false;
    $(".title-list li").each(function () {
      if ($(this).hasClass("active")) {
        fromCache = true;
      }
    });

    // 渲染左侧数据
    function goods_class(topClass) {
      var html = '';
      var firstId = '';
      for (var i in topClass) {
        html += '<li class="';
        if(i == 1){
          html+='active';
          firstId = topClass[i].gc_id;
        }
        html += '" data-id="' + topClass[i].gc_id + '">' + topClass[i].gc_name + '</li>';
      }
      $(".title-list").html(html);
      type_list(firstId);
    }

    // 获取右侧数据
    function type_list(id) {
      var parm = {gc_id: id};
      var goodsClass = store.get('goods_class' + id);
      if (typeof goodsClass != 'undefined') {
        update_list(goodsClass);
        return false;
      }
      ajax('get', 'goods_class', '', parm, function (ret) {
        var data = ret.datas;
        store.set('goods_class' + id, data);
        update_list(data);
      });
    }

    // 渲染右侧数据
    function update_list(data) {
      var html = template('catlog', data);
      $(".type-list").html(html);
    }

    //进入分类页面,判断是否是缓存
    if (!fromCache) {
      var data = store.get('top_class');
      if (!data) {
        ajax('get', 'goods_class','index2', '', function (ret) {
          store.set('top_class', ret.datas.class_list);
          goods_class(ret.datas.class_list);
        })
      } else {
        goods_class(data);
      }
    }

    // 点击左侧菜单按钮 更新右侧数据
    var titleList = $(".title-list");
    $(titleList).on('click', 'li', function () {
      if ($(this).hasClass('active')) {
        return false;
      }
      $(titleList).find("li").removeClass('active');
      $(this).addClass('active');
      var id = $(this).attr("data-id");
      type_list(id);
    });

    //跳转到商品列表页面
    // var goodsParm = store.get('goodsParm');
    // if(typeof goodsParm == 'undefined') {
    //   goodsParm = {
    //     //gc_id : '',
    //     curpage : 1,
    //     page : 8,
    //     key: 4,
    //     order: 1
    //     //keyword : ''
    //   };
    //   store.set('goodsParm',goodsParm);
    // }
    $(".type-list").off('click','a').on('click', 'a', function () {
      goodsParm.gc_id = $(this).attr("data-gcid");
      goodsParm.keyword = '';
      store.set('goodsParm',goodsParm);
      $.router.load('#goods');
    });

    //执行搜索
    // $("input").off('keypress').keypress(function(e) {
    //   if(e.which == 13) {
    //     goodsParm.gc_id = '';
    //     goodsParm.keyword = $('#search').val();
    //     store.set('goodsParm',goodsParm);
    //     document.activeElement.blur();
    //     $.router.load('#goods');
    //   }
    // });

  });

  /**
   * goods商品列表
   */
  $(document).on("pageInit", "#goods", function (e, id, page) {
    $(page).find(".content").on("scroll", function () {
      bLazy.revalidate();
    });

    var getGoodsList = function (data, reload) {
      if (reload) {
        data.curpage = 1;
        $(".goods-list").empty();
      }
      ajax('get', 'goods','goods_list206', data, function (ret) {
        goodsResult.curpage = goodsParm.curpage;
        if (!ret.hasmore) {
          goodsResult.hasMore = false;
          $(page).find('.more').html('');
        }
        //添加无限加载图标
        if(reload && ret.hasmore){
          $(page).find('.more').html('<div class="infinite-scroll-preloader"><div class="preloader"></div></div>')
        }

        renderHtml($('#good_list'),'goods',ret.datas,true);
        //首屏
        if(reload){
          bLazy.revalidate();
        }
        $.refreshScroller();
        sort = true;
      }, function () {
        sort = true;
        // 如果ajax请求失败,更改请求的curpage 使页面可以继续请求
        goodsParm.curpage = goodsResult.curpage;
      });
    };

    var goodsParm = store.get('goodsParm');

    // 获取列表数据
    var goodsResult ={
      hasMore: true,
      curpage: 1
    };
    // 获取数据  reload = true 刷新页面
    getGoodsList(goodsParm, true);

    //销量、价格排序
    var $buttonsBar = $('#buttons_tab');
    var sort = true;
    $buttonsBar.off('click','a').on('click','a',function () {
      sort = false;
      var $this = $(this);
      var key = $this.data('key');
      if($this.hasClass('active') && key != 3){ return false}
      $this.siblings().removeClass('active');
      $this.addClass('active');
      if(key == 3){
        var order = $this.data('order');
        var _order = order - 1 > 0 ? '1' : '2';
        $this.attr('data-order',_order);
      }
      goodsParm['key'] = key;
      goodsParm['order'] = _order;
      store.set('goodsParm',goodsParm);
      //console.log('jjj');
      getGoodsList(goodsParm, true);
      //$.router.load('#goods','true','true');
    });

    //无限加载
    $(page).off('infinite').on('infinite', function () {
      // console.log('监控无限加载s');
      // console.log(goodsResult.curpage);
      // console.log(goodsParm.curpage);
      // console.log(goodsResult.hasMore);
      // console.log(sort);
      if (goodsResult.curpage != goodsParm.curpage || !goodsResult.hasMore ||!sort) {
        return false;
      } else {
      // console.log('监控无限加载e');
        goodsParm.curpage = goodsResult.curpage + 1;
        getGoodsList(goodsParm);
      }
    });

    //下拉更新
    var $content = $(page).find(".content").on('refresh', function (e) {
      //console.log('下拉更新');
      getGoodsList(goodsParm, true);
      $.pullToRefreshDone($content);
    });

  });

  /**
   * item 商品详情
   */
  $(document).on("pageInit", "#item", function(e, id, page) {
    //微信分享S
    var wxconfig = []; //定义一个全局的保存微信配置的变量
    $.ajax({
      async: false, //这里设为同步请求（重要）
      type: 'POST',
      url: 'http://api.htths.com/api/wx-jssdk/get_ticket.php', //被请求的网址
      data: {url:document.URL}, //将当前调用网址发回给服务器做签名用
      dateType: 'JSON',
      cache: false,
      success: function(result) {
        wxconfig = JSON.parse(result);
      },
      error:function() {
        alert('微信分享初始化失败');
      }
    });

    wx.config({
      appId: wxconfig.appId,
      timestamp: wxconfig.timestamp,
      nonceStr: wxconfig.nonceStr,
      signature: wxconfig.signature,
      jsApiList: [
        // 所有要调用的 API 都要加到这个列表中
        'onMenuShareTimeline', //分享到朋友圈
        'onMenuShareAppMessage', //分享给朋友
        'onMenuShareQQ' //分享到QQ
      ]
    });
    //微信分享E

    //后退前,将tab切换到basic标签下
    $(page).on('click','a.back',function () {
      var jBasic = $('.jBasic');
      $.showTab(jBasic.data("tab") || jBasic.attr('href'), jBasic);
    });

    $("#kefu").off('click').click(function () {
      _MEIQIA('showPanel');
    });

    var _goods_id = session.get('good_id');
    var conditions = {goods_id:_goods_id};

    update(conditions,true);
    uLike(_goods_id);

    //下拉更新
    var $content = $(page).find(".content").on('refresh', function(e) {
      console.log(5);
      conditions.curpage = 1;
      update(conditions,true);
      $.pullToRefreshDone($content);
    });

    function update(conditions,first){
      var $goodBasic = $("#good-basic");
      $goodBasic.html('');
      ajax('get','goods','goods_detail',conditions,function(ret){
        var data = ret.datas;
        if(data.goods_info.xianshi_info.xianshi_id != undefined){
          $("#buy_now").attr('data-if-xianshi','true');
        }else {
          $("#buy_now").attr('data-if-xianshi','false');
        }

        data.slide = data.goods_image.split(',');
        var html = template('good-basic', data);
        wx.ready(function () {
          var options = {
            title: data.goods_info.goods_name, // 分享标题
            link: 'http://m.htths.com/#item?goods_id='+ data.goods_info.goods_id, // 分享链接，记得使用绝对路径
            // link: 'http://m.htths.com/#item?goods_id='+ data.goods_info.goods_id + 'share', // 分享链接，记得使用绝对路径
            imgUrl: data.goods_image, // 分享图标，记得使用绝对路径
            desc: data.goods_info.goods_jingle, // 分享描述
            success: function () {
//            console.info('分享成功！');
//            $.alert('谢谢您的分享!');
            },
            cancel: function () {
              // alert('取消分享');
//            console.info('取消分享！');
            }
          }
          wx.onMenuShareTimeline(options); // 分享到朋友圈
          wx.onMenuShareAppMessage(options); // 分享给朋友
          wx.onMenuShareQQ(options); // 分享到QQ
          // 在这里调用 API
        });

        $goodBasic.html(html);
        $("#slide").swiper({
          pagination: '#slide > .swiper-pagination'
        });


        //收藏商品
        var $favorate = $('#favorate');
        $favorate.off('click').click(function () {
          var $this = $(this);
          var op = $this.hasClass('active') ? 'favorites_del' : 'favorites_add';
          ajax('post','member_favorites', op, conditions,function(ret){
            op == 'favorites_add' ? $favorate.addClass('active') : $favorate.removeClass('active');
          });

        });


      });



    }

    // 猜你喜欢
    var $uLike = $("#ulike");
    function uLike(goods_id){
      var data = {goods_id:goods_id,number:4};
      ajax('get','goods','goods_rand_list',data,function(ret){
        var goods_rand_item = ret.datas;
        var html = '';
        for(id in goods_rand_item){
          html += '<li>\
               <a href="" class="jGo" data-good_id="'+ goods_rand_item[id].goods_id +'">\
                 <img src="'+ goods_rand_item[id].goods_image_url +'" alt="">\
                 <h5>'+ goods_rand_item[id].goods_name +'</h5>\
               </a>\
             </li>';
        }
        $uLike.find('ul').html(html);
      });
    }

    //口味选择
    var goodBasic = $('#good-basic');
    goodBasic.off('click','a').on('click','a',function () {
      var _goods_id = $(this).data('good_id');
      session.set('good_id',_goods_id);
      conditions.goods_id = _goods_id;
      update(conditions,true);
    });

    $("#buy_now").off('click').click(function () {
      var _goods_id = sessionStorage.getItem('good_id');
      if($("#buy_now").attr('data-if-xianshi')){
        cartAdd(_goods_id,1,function(cartnum){
          $('.cart-num').text(cartnum);
          $.toast('添加购物车成功');
        });
        return false;
      }


      var cartParm = {
        cart_id : _goods_id + '|1',
        ifcart : 0
      };
      session.set('cartParm', cartParm);
      $.router.load('#buy');
      
    });

    // 加入商品到购物车
    $("#add_cart").off('click').click(function () {
      cartAdd(_goods_id,1,function(cartnum){
        $('.cart-num').text(cartnum);
        $.toast('添加购物车成功');
      });
    });

    function cartAdd(goods_id, quantity,callback){
      if (quantity == undefined) {
        quantity = 1;
      }
      ajax('post','member_cart','cart_add',{goods_id:goods_id,quantity:quantity},function(ret){
        var cartnum = ret.datas.cart_count;
        store.set('cartnum', cartnum);
        callback(cartnum);
      },function (ret) {
          $.confirm('促销活动仅限App,前往下载?', function () {
            window.location.href="http://a.app.qq.com/o/simple.jsp?pkgname=com.htths.mall";
          });
      });
    }

    //打开商品详情
    var $picture = $('#picture');
    $('.jPicture').off('click').click(function () {
      $picture.html('');
      ajax('get','goods','goods_body',{goods_id:_goods_id},function(ret){
        $picture.html(ret.datas);
      });
    });

    //打开商品评论
    var $comment = $("#good_comment");
    var hasmoreComment = false;
    $('.jComment').off('click').click(function () {
      $comment.html('');
      ajax('get','goods','goods_evaluate',{'goods_id':_goods_id,'type': 1},function(ret){
        hasmoreComment = ret.hasmore;
        renderHtml($comment,'comment', ret.datas);
      });
    });

    //商品评论的无限加载S
    var curpage = 2;
    var requestMore = true;
    $(page).on('infinite',function () {
      if(!requestMore) return false;
      requestMore = false;
      if($(this).find('.infinite-scroll.active').attr('id') == "good_comment" && hasmoreComment){
        ajax('get','goods','goods_evaluate',{'goods_id':_goods_id,'type': 1,curpage:curpage},function(ret){
          requestMore=ret.hasmore;
          if(requestMore) curpage++;
          renderHtml($comment,'comment', ret.datas,true);
        });
      }
    });//商品评论的无限加载E

  });

  /**
   * cart购物车
   */
  $(document).on("pageInit", "#cart", function (e, id, page) {
    var $cartList = $("#cart_list");

    // 初始数据
    ajax('post','member_cart','cart_list','',function(ret){
      var cartData = ret.datas;
      console.log(cartData);
      updateList(cartData);
    });

    //加
    $cartList.off('click','.jQtyAdd').on('click','.jQtyAdd',function () {
      var _this = $(this);
      var cartNum = _this.attr('data-num') - 0 + 1;
      var cartId = _this.data('cart_id');
      updateNum(cartId,cartNum);
    });

    //减
    $cartList.off('click','.jQtyMin').on('click','.jQtyMin',function () {
      var _this = $(this);
      var cartNum = _this.attr('data-num') - 0 - 1;
      var cartId = _this.data('cart_id');
      updateNum(cartId,cartNum);
    });

    function updateNum(cart_id,num) {
      var data = {cart_id:cart_id,quantity:num};
      ajax('post','member_cart','cart_edit_quantity',data,function(ret){
        var cartData = ret.datas;
        updateList(cartData);
      });
    }

    //删除
    $cartList.off('click','.jDel').on('click','.jDel',function () {
      var _this = $(this);
      var cartId = _this.data('cart_id');
      $.confirm('确定要删除?', function () {
        ajax('post','member_cart','cart_del',{cart_id:cartId},function(ret){
          updateList(ret.datas);
        });
      },function () {
        return false;
      });
    });

    //勾选/去勾选
    $cartList.off('click','.radio').on('click','.radio',function () {
      var $this = $(this);
      var cartId = $this.data('cart_id');
      var checked = $this.attr('data-checked') == 1 ? 0 : 1;
      updateChecked(cartId,checked);
    });

    //全选
    $('#cart-all-checked').off('click').click(function () {
      var _this = $(this);
      var cartId = _this.data('cart_id');
      var checked = _this.hasClass('icon-roundcheck') ? 1:0;
      updateChecked(cartId,checked);
    });

    function updateChecked(cartId, checked) {
      var data = {cart_id:cartId,cart_checked:checked};
      ajax('post','member_cart','cart_checked',data,function(ret){
        updateList(ret.datas);
      });
    }

    function updateList(cartData) {
      renderHtml($cartList,'cart', cartData);
      store.set('cartnum',cartData.cart_num);
      $('.cart-num').text(cartData.cart_num);
      var $cartAllChecked = $('#cart-all-checked');
      $('#need2pay').text(cartData.need2pay);
      $cartAllChecked.data('cart_id',cartData.cart_all_item);
      if(cartData.cart_all_checked){
        $cartAllChecked.removeClass('icon-roundcheck');
        $cartAllChecked.addClass('icon-roundcheckfill');
        $cartAllChecked.parent().addClass('active');
      }else{
        $cartAllChecked.removeClass('icon-roundcheckfill');
        $cartAllChecked.addClass('icon-roundcheck');
        $cartAllChecked.parent().removeClass('active');
      }

      var $jStep1 = $('#jStep1');
      if(!cartData.allow_submit){
        $jStep1.removeClass('red-color');
        $jStep1.addClass('disable');
      }else{
        var cartParm = {
          cart_id : cartData.cart_checked_item,
          ifcart : 1
        };
        session.set('cartParm', cartParm);
        $jStep1.off('click').click(function () {
          $.router.load('#buy');
        })
      }
    }

  });

  /**
   * buy结算
   */
  $(document).on("pageInit", "#buy", function (e, id, page) {
    var cartParm = session.get("cartParm");
    console.log(cartParm);
    var $step2 = $('#step2');

    // 初始数据
    ajax('post','member_buy','buy_step1',cartParm,function(ret){
      var data = ret.datas;
      cartParm.address_id = data.selected_address_id;
      session.set('cartParm', cartParm);
      data.deliveryText = cartParm.ifchain == 1 ? '上门自提':'快递';
      renderHtml($('#buy_wrapper'),'buy',data);
      $('#total-pay').text('¥' + data.need2pay);
      if(!data.allow_submit){
        $step2.addClass('button-gray');
      }else{
        $step2.removeClass('button-gray');
      }
    });


    $step2.off('click').click(function () {
      if($(this).hasClass('button-gray')){
        return false
      }
      var cartParm = session.get("cartParm");
      cartParm['ref_id'] = session.get("ref_id");
      ajax('post','member_buy','buy_step2',cartParm,function(ret){
        session.set('orderPay', ret.datas);
        session.set("ref_id",'');
        $.router.load('#pay');
      });

    });

    $(page).off('click','#delivery').on('click','#delivery',function () {
      var buttons = [
        {
          text: '请选择',
          label: true
        },
        {
          text: '快递',
          bold: true,
          color: 'danger',
          onClick: function() {
            $.router.load("#address",'true','true');
            $.closeModal();
          }
        },
        {
          text: '自提',
          onClick: function() {
            $.router.load("#chain",'true','true');
            $.closeModal();
          }
        }
      ];
      $.actions(buttons);

    });



  });

  /**
   * pay 支付
   */
  $(document).on("pageInit", "#pay", function (e, id, page) {
    var orderPay = session.get('orderPay');
    $('#order-pay').text('¥' + orderPay.order_total);
    var key = store.get('token').key;
    //console.log('http://api.htths.com/index.php?act=member_payment&op=pay_new&payment_code=wxpay_jsapi&pay_sn=' + orderPay.pay_sn  + '&key=' + key);

    $('#pay-now').off('click').click(function () {
      var checkedItem = $("input[name=pay]:checked").val();
      if(!checkedItem){
        $.alert('请选择支付方式');
        return false;
      }
      // console.log('http://api.htths.com/index.php?controller=member_payment&action=pay_new&payment_code='+checkedItem+'&pay_sn=' + orderPay.pay_sn  + '&key=' + key);
      location.href = 'http://api.htths.com/index.php?controller=member_payment&action=pay_new&payment_code='+checkedItem+'&pay_sn=' + orderPay.pay_sn  + '&key=' + key;
    });

  });

  /**
   * address 收货地址
   */
  $(document).on("pageInit", "#address", function (e, id, page) {
    var $addressWrapper = $("#address-wrapper");
      ajax('post','member_address','address_list','',function(ret){
        renderHtml($addressWrapper,'address',ret.datas);
      });

    //点击切换收货地址
    $addressWrapper.off('click','.address-item').on('click','.address-item',function () {
      var cartParm = session.get('cartParm');
      cartParm.address_id = $(this).closest('.card').attr('data-address-id');
      cartParm.chain_id = '';
      cartParm.ifchain = '';
      session.set('cartParm', cartParm);
      $.router.load("#buy",'true','true');
    });

    //点击编辑收货地址
    $addressWrapper.off('click','a.edit').on('click','a.edit',function () {
      var addressId = $(this).closest('.card').attr('data-address-id');
      session.set('addressId', addressId);
      $.popup('.popup-address-edit')
    });

    //点击删除收货地址
    $addressWrapper.off('click','a.del').on('click','a.del',function () {
      var $card = $(this).closest('.card');
      var addressId = $card.attr('data-address-id');
      $.confirm('确定要删除?', function () {
        ajax('post','member_address','address_del',{address_id:addressId},function(ret){
          $card.remove();
          var $addressCount = $addressWrapper.find('#address-count');
          var newCount = $addressCount.text() -0 -1;
          $addressCount.text(newCount);
        });
      });
    });

    //点击设为默认收货地址
    $addressWrapper.off('click','a.default').on('click','a.default',function () {
      var $this = $(this);
      var $roundcheck = $this.find('i');
      if($roundcheck.hasClass('icon-roundcheckfill')){
        return false;
      }
      var $card = $this.closest('.card');
      var addressId = $card.attr('data-address-id');
      ajax('post','member_address','set_default_address',{address_id:addressId},function(ret){
        var $roundcheckfill = $addressWrapper.find('i.icon-roundcheckfill');
        $roundcheckfill.removeClass('icon-roundcheckfill');
        $roundcheckfill.addClass('icon-roundcheck');
        $roundcheck.removeClass('icon-roundcheck');
        $roundcheck.addClass('icon-roundcheckfill');
      },function(){
        console.log('稍后重试');
      });
    });

    //添加新的收货地址
    $addressWrapper.off('click','#add-address').on('click','#add-address',function () {
      console.log('shouhuodizhi');
      session.set('addressId', '');
      $.popup('.popup-address-edit');
    });

  });

  /**
   * account 用户中心
   */
  $(document).on("pageInit", "#account", function (e, id, page) {
    bLazy.revalidate();
    ajax('post','member_index','index206','',function(ret){
      var member_info=ret.datas.member_info;
      if(member_info.avator){
        $('#avator').attr("src",member_info.avator);
      }
      $("#nickname").text(member_info.member_name);
      if(member_info.order_nopay_count > 0){
        var $nopayCount = $('#nopay_count');
        $nopayCount.text(member_info.order_nopay_count);
        $nopayCount.css('display','inline-block');
      }
      if(member_info.order_payed_count > 0){
        var $payedCount = $('#payed_count');
        $payedCount.text(member_info.order_payed_count);
        $payedCount.css('display','inline-block');
      }
      if(member_info.order_noreceipt_count > 0){
        var $noreceiptCount = $('#noreceipt_count');
        $noreceiptCount.text(member_info.order_noreceipt_count);
        $noreceiptCount.css('display','inline-block');
      }
      if(member_info.order_noeval_count > 0){
        var $noevalCount = $('#noeval_count');
        $noevalCount.text(member_info.order_noeval_count);
        $noevalCount.css('display','inline-block');
      }

      var orderCondition = {
        curpage : 1,
        page : 8,
        order_state: 4
      };

      $('#orderState').find('a').off('click').click(function () {
        orderCondition.order_state = $(this).attr('data-order-state');
        store.set('orderCondition',orderCondition);
      });

    });
    //退出登录
    $('.jLogout').off('click').click(function () {
      var buttons1 = [
        {
          text: '退出登录',
          bold: true,
          color: 'danger',
          onClick: function() {
            var data=store.get('token');
            data.client = 'wap';
            ajax('post','logout','',data,function(ret){
              store.set('token','');
              $.alert('成功登出');
              $.router.load('#home');
            },function(msg){
              $.alert(msg);
            });
          }
        }
      ];
      var buttons2 = [
        {
          text: '取消'
        }
      ];
      $.actions([buttons1, buttons2]);


    });

  });

  /**
   * order订单列表
   */
  $(document).on("pageInit", "#order", function (e, id, page) {
    var orderCondition = store.get('orderCondition');
    var buttonsBar = $(page).find('.buttons-tab a');
    var curpage = 1;
    var hasmore = false;
    buttonsBar.each(function () {
      var $this = $(this);
      if($this.attr('data-order-state') == orderCondition.order_state){
        $this.addClass('active');
      }else{
        $this.removeClass('active');
      }
    });

    $(buttonsBar).off('click').click(function () {
      var $this = $(this);
      var orderState = $this.attr('data-order-state');
      if($this.hasClass('active')){ return false}
      $this.siblings().removeClass('active');
      $this.addClass('active');

      orderCondition.order_state = orderState;
      getList(orderCondition, true);

    });

    // 获取列表数据
    getList(orderCondition, true);

    //无限加载
    $(page).on('infinite', function () {
      console.log(3);
      if (!hasmore) {
        return false;
      } else {
        orderCondition.curpage = curpage + 1;
        getList(orderCondition);
       }
    });

    //下拉更新
    var $content = $(page).find(".content").on('refresh', function (e) {
      getList(orderCondition, true);
      $.pullToRefreshDone($content);
    });

    // 获取数据  reload = true 刷新页面
    function getList(orderCondition, reload) {
      if (reload) {
        orderCondition.curpage = 1;
      }
      ajax('post', 'member_order','order_list206', orderCondition, function (ret) {
        curpage = orderCondition.curpage;
        hasmore = ret.datas.hasmore;

        // 刷新页面
        if (reload) {
          $("#order_wrapper").empty();
        }

        if(ret.datas.hasmore){
          $.toast('当前' + orderCondition.curpage + '页,共' + ret.datas.page_total + '页');
        }
        updateList(ret.datas);

      }, function () {
        // 如果ajax请求失败,更改请求的curpage 使页面可以继续请求
        orderCondition.curpage = curpage;
      });
    }

    // 更新列表
    function updateList(data) {
      renderHtml($('#order_wrapper'),'my-order',data);
      $.refreshScroller();
    }

    //前往订单详情
    $(page).off('click','.card-content').on('click','.card-content',function () {
      session.set('order_id', $(this).data('order_id'));
      $.router.load('#order_detail');
    });

  });


  /**
   * order_detail 订单详情
   */
  $(document).on("pageInit", "#order_detail", function (e, id, page) {
    var orderId = session.get('order_id');
    ajax('post','member_order','order_info206',{order_id:orderId},function(ret){
      renderHtml($(page).find('.content'),'order_detail',ret.datas.order_info);
      var data={
        'order_id': ret.datas.order_info.order_id,
        'extend_order_goods' : ret.datas.order_info.extend_order_goods
      };
      session.set('order_item',data);
    });

  });

  /**
   * order_evaluate 订单点评
   */
  $(document).on("pageInit", "#order_evaluate", function (e, id, page) {
    var orderInfo = session.get('order_item');
    console.log(orderInfo);
    renderHtml($(page).find('.content'),'order_evaluate',orderInfo);

    //匿名的选择
    $(page).off('click','.jAnony').on('click','.jAnony',function () {
      var $this = $(this);
      if($this.hasClass('icon-roundcheck')){
        $this.removeClass('icon-roundcheck');
        $this.addClass('icon-roundcheckfill');
      }else{
        $this.removeClass('icon-roundcheckfill');
        $this.addClass('icon-roundcheck');
      }
    });

    //星选择
    $(page).on('click','.icon-start',function () {
      var $this = $(this);
      var $seclectedSrart = $this.parent();
      var seclectedStart = $seclectedSrart.data('start');
      var touchedStart =  $this.data('start');
      if(touchedStart != seclectedStart){
        $seclectedSrart.removeClass('starts-' + seclectedStart);
        $seclectedSrart.addClass('starts-' + touchedStart);
        $seclectedSrart.attr('data-start',touchedStart);
      }

    });

    $(page).on('click','#evaluate_btn',function () {
      var data = new Array();
      $(page).find('.item-inner').each(function () {
        var $this = $(this);
        var rec_id = $this.data('rec_id');
        var star = $(this).find('.item-title').data('start');
        var content = $(this).find('textarea').val();
        var anony = $(this).find('.jAnony').hasClass('icon-roundcheckfill') ? '1' : '0';
        if (!content) {
          $.alert('请填写评论内容');
          return false;
        }
        data.push(rec_id+'|'+star+'|'+content+'|'+anony);
      });

      var order_id = $(this).data('order_id');
      ajax('post','member_evaluate','add',{order_id:order_id,evaluations:data.join()},function(ret){
        $.alert('评价成功');
        $.router.back()
      });


    })


  });

  /**
   *  订单列表,订单详情共用的订单状态操作
   */
  //进入订单商品评价
  // $(document).off('click','.jEvaluate').on('click','.jEvaluate',function () {
  //   console.log(1123)
  //   $.router.load('#order_evaluate');
  // });

  //改变订单状态的操作
  $(document).off('click','.jOrderOp').on('click','.jOrderOp',function () {
    var $this = $(this);
    var orderId = $this.parent().data('order_id');
    var $card =  $this.closest('.card');
    var opType = $this.data('optype');
    var message = '';
    switch (opType){
      case 'order_cancel':
        message = '确定要取消?';
        break;
      case 'order_del':
        message = '确定要删除?';
        break;
      case 'order_receive':
        message = '确定要收货?'
    }
    $.confirm(message, function () {
      ajax('post','member_order',opType,{order_id:orderId},function(ret){
        $card.remove();
      });
    });
  });

  //去支付
  $(document).on('click','.jGoPay',function () {
    var $this = $(this);
    session.set('orderPay', {"pay_sn":$this.attr('data-pay_sn'),"order_total":$this.data('order_total')});
  });

  //查看物流
  $(document).off('click','.jSearchDeliver').on('click','.jSearchDeliver',function () {
    session.set('order_sn', $(this).parent().attr('data-order_sn'));
    $.popup('.popup-deliver');
  });


  /**
   * favorite 我的关注
   */
  $(document).on("pageInit", "#favorite", function (e, id, page) {
    $(page).find(".content").on("scroll", function () {
      bLazy.revalidate();
    });

    var favoriteParm = {
      page: 8,
      curpage: 1
    };
    var $favoriteList = $('#favorite_list');
    var hasmore = false;

    // 获取列表数据
    getList(favoriteParm,true);

    //无限加载
    $(page).on('infinite', function () {
      if (!hasmore) {
        return false;
      } else {
        getList(favoriteParm);
        $.refreshScroller();
      }
    });

    // 获取数据  reload = true 刷新页面
    function getList(reload) {
      if (reload) {
        favoriteParm.curpage = 1;
        $favoriteList.empty();
      }
      ajax('post', 'member_favorites','favorites_list', '', function (ret) {

        if (!ret.hasmore) {
          $(page).find('.more').html('');
        }
        if(reload && ret.hasmore){
          $(page).find('.more').html('<div class="infinite-scroll-preloader"><div class="preloader"></div></div>')
        }

        favoriteParm.curpage ++;
        hasmore = ret.datas.hasmore;
        renderHtml($favoriteList,'favorite',ret.datas,true);
        bLazy.revalidate();
      });
    }

  });

  /**
   * favorite 我的评价
   */
  $(document).on("pageInit", "#my-comments", function (e, id, page) {
    $(page).find(".content").on("scroll", function () {
      bLazy.revalidate();
    });

    var myParm = {
      page: 8,
      curpage: 1
    };
    var $commentsList = $('#comments_list');
    var hasmore = false;

    // 获取列表数据
    getList(myParm,true);

    //无限加载
    $(page).on('infinite', function () {
      if (!hasmore) {
        return false;
      } else {
        getList(myParm);
        $.refreshScroller();
      }
    });

    // 获取数据  reload = true 刷新页面
    function getList(reload) {
      if (reload) {
        myParm.curpage = 1;
        $commentsList.empty();
      }
      ajax('post', 'member_evaluate','list', '', function (ret) {
        if (!ret.hasmore) {
          $(page).find('.more').html('');
        }
        if(reload && ret.hasmore){
          $(page).find('.more').html('<div class="infinite-scroll-preloader"><div class="preloader"></div></div>')
        }

        myParm.curpage ++;
        hasmore = ret.datas.hasmore;
        renderHtml($commentsList,'my_comments',ret.datas,true);
        bLazy.revalidate();
      });
    }

  });

  //注册,找回密码,修改密码
  $('.jEyes').off('click').click(function () {
    var $this = $(this);
    var $passwordInput = $this.closest('.item-inner').find('input');
    if($this.hasClass('icon-attentionforbid')){
      $this.removeClass('icon-attentionforbid');
      $this.addClass('icon-attention');
      $passwordInput.attr('type','text');
    }else{
      $this.removeClass('icon-attention');
      $this.addClass('icon-attentionforbid');
      $passwordInput.attr('type','password');
    }
  });

  //获取手机验证码
  $('.jGetCaptcha').off('click').click(function () {
    var $this = $(this);
    var smsType = $this.data('sms_type');
    var phoneNumber = $('#mobile' + smsType).val();
    if(!checkMobile(phoneNumber)){ return false;}
    if($this.hasClass('disabled')) {return false;}
    var i = 15;
    $this.addClass('disabled');
    var timer = setInterval(function () {
      i -= 1;
      if(i > 0){
        $this.text(i+ '秒后重试');
      }else{
        $this.removeClass('disabled');
        $this.text('获取验证码');
        clearInterval(timer);
      }
    }, 1000);


    $.ajax({
      url : 'http://i.htths.com/index.php?controller=connect_sms&action=get_captcha',
      type : 'POST',
      data : {phone:phoneNumber,type:smsType},
      dataType : 'json',
      success : function (ret) {
        console.log(ret);
        if (ret.code == 200){
          $.alert('验证码已发');
        }else{
          $.toast(ret.datas.error);
          $this.removeClass('disabled');
          $this.text('获取验证码');
          clearInterval(timer);
        }
      },
      error: function(xhr, type){
        $this.removeClass('disabled');
        $this.text('获取验证码');
        clearInterval(timer);
      }

    })

  });

  /**
   * login 用户登录/注册
   */
  $(document).on("pageInit", "#login", function (e, id, page) {
    $('#closeLogin').off('click').click(function () {
      $.router.load('#home');
    });

    //登录
    $("#login_btn").off('click').click(function () {
      var $this = $(this);
      var data = {
        username : $("input[name=username]").val(),
        password : $("input[name=password]").val(),
        client   : 'wap'
      };
      if (!data.username) {
        $.alert('请输入用户名');
        return;
      }
      if (!data.password) {
        $.alert('请输入密码');
        return;
      }
      $this.text('登录中..');
      ajax('post','login','',data,function(ret){
        // 下次打开页面 还是打开之前的 所以恢复一下按钮
        $this.text('登录');
        store.set('token', {'key':ret.datas.key , 'username':ret.datas.username});
        $.router.back()
      },function(){
        $this.text('登录');
      });
    });

    //注册
    $('#sign_btn').off('click').click(function () {
      var phoneNumber = $('#mobile1').val();
      if(!checkMobile(phoneNumber)){ return false;}
      var data={
        'phone' : phoneNumber,
        'sms_captcha' : $('#captcha_code1').val(),
        'username' : $('#username_sign').val(),
        'password' : $('#password_sign').val(),
        'client' : 'wap'
      };

      if(!data.sms_captcha){
        $.alert('请输入手机验证码');
        return false;
      }
      if(!data.password){
        $.alert('请输入密码');
        return false;
      }
      ajax('post','login','sms_register',data,function(ret){
        store.set('token', {'key':ret.datas.key , 'username':ret.datas.username});
        $.router.back();
      },function(msg){
        $.alert(msg);
      });
    });

  });


  /**
   * setnew_password 重新设置密码
   */
  $(document).on('pageInit','#setnew_password', function (e, id, page) {
    $('#password_new').off('click').click(function () {
      var data={
        'old_password' : $('#old_password').val(),
        'password' : $('#new_password').val()
      };

      if(!data.old_password){
        $.alert('请输入旧的密码');
        return false;
      }
      if(!data.password){
        $.alert('请输入新密码');
        return false;
      }
      ajax('post','member_account','modify_pwd',data,function(ret){
        store.set('token', '');
        $.alert('修改成功,请重新登录');
        $.router.back();
      });
    });


  });

  /**
   * 找回密码
   */
  $(document).on('opened','.popup-find-password', function () {
    $('#password_reset').off('click').click(function () {
      var phoneNumber = $('#mobile3').val();
      if(!checkMobile(phoneNumber)){ return false;}
      var data={
        'phone' : phoneNumber,
        'sms_captcha' : $('#captcha_code3').val(),
        'password' : $('#password_find').val()
      };

      if(!data.sms_captcha){
        $.alert('请输入手机验证码');
        return false;
      }
      if(!data.password){
        $.alert('请输入密码');
        return false;
      }
      ajax('post','login','find_password',data,function(ret){
        store.set('token', '');
        $.closeModal('.popup-find-password');
        $.alert('请用新密码进行登录');
      });
    });

  });

  /**
   * chain 门店
   */
  $(document).on('pageInit','#chain', function (e, id, page) {
    $('.item-link').off('click').click(function () {
      var cartParm = session.get("cartParm");
      cartParm.chain_id = 1;
      cartParm.ifchain = 1;
      session.set('cartParm', cartParm);
      $.router.load("#buy",'true','true');
    });

  });

  /**
   * setting 设置
   */
  $(document).on('pageInit','#setting', function (e, id, page) {
    var storageSize = Math.round(encodeURIComponent(JSON.stringify(localStorage)).length / 1024);
    var cacheSizeText = storageSize > 1024 ? Math.round(storageSize /1024) + 'M' : storageSize + 'K';
    $('#cacheSize').text(cacheSizeText);

    //退出登录
    $(page).off('click','#logout').on('click','#logout',function () {
      var buttons1 = [
        {
          text: '退出登录',
          bold: true,
          color: 'danger',
          onClick: function() {
            var data=store.get('token');
            data.client = 'wap';
            ajax('post','logout','',data,function(ret){
              store.set('token','');
              $.alert('成功登出')
            },function(msg){
              $.alert(msg);
            });
          }
        }
      ];
      var buttons2 = [
        {
          text: '取消'
        }
      ];
      $.actions([buttons1, buttons2]);


    });

  });

  /**
   * 地址编辑
   */
  $(document).on('opened','.popup-address-edit', function () {
    var addressId = session.get('addressId');
    var $addrEditWrapper = $('#addr-edit-wrapper');
    console.log(addressId);
    if(addressId > 0){
      ajax('post','member_address','address_info',{address_id:addressId},function(ret){
        renderHtml($addrEditWrapper,'addr-edit', ret.datas);
      },function(msg){
        $addrSubmit.text('提交');
      });
    }else{
      var addressInfo = {
        address_info:{
          address: "",
          address_id: "",
          area_id: "",
          area_info: "",
          bpic: "",
          city_id: "",
          dlyp_id: "",
          fpic: "",
          idcard_back: "",
          idcard_front: "",
          idcard_no: "",
          is_default: "",
          member_id: "",
          mob_phone: "",
          pr_id: "",
          tel_phone: '',
          true_name: ''
        }
      };
      var area = store.get('area');
      if(area){
        addressInfo.address_info.pr_id = area.area1;
        addressInfo.address_info.city_id = area.area2;
        addressInfo.address_info.area_id = area.area3;
        addressInfo.address_info.area_info = area.areainfo;
      }
      renderHtml($addrEditWrapper,'addr-edit', addressInfo);

      // if(area){
      //   $("#area1").val(area.area1);
      //   $("#area2").val(area.area2);
      //   $("#area3").val(area.area3);
      //   $("#area-info").text(area.areainfo);
      // }
    }

    // 提交表单
    var $addrSubmit = $('#addr-submit');
    $addrSubmit.off('click').click(function () {
      submit();
    });
    function submit(){
      var data = {
        address_id: addressId,
        pr_id: $("#area1").val(),
        city_id: $("#area2").val(),
        area_id: $("#area3").val(),
        area_info: $("#area-info").text(),
        address : $("#addr-detail").val(),
        true_name : $("#true-name").val(),
        mob_phone : $("#mob-phone").val(),
        idcard_no : $("#idcard_no").val(),
        is_default : 1,
        client   : 'wap'
      };
      if (!data.pr_id || !data.city_id || !data.area_id) {
        $.alert('请选择区域');
        return;
      }
      if (!data.address) {
        $.alert('请输入收货地址');
        return;
      }
      if (!data.true_name) {
        $.alert('请输入收货人姓名');
        return;
      }
      if (!data.mob_phone) {
        $.alert('请输入手机号');
        return;
      }
      $addrSubmit.text('提交中..');
      ajax('post','member_address','address_addedit',data,function(ret){
        $addrSubmit.text('提交');
        $.closeModal('.popup-address-edit');
        //document.location.reload(true);
        var page = '#' + JSON.parse(sessionStorage.getItem('sm.router.currentState')).pageId;
        $.router.load(page,'true','true');
      },function(msg){
        $addrSubmit.text('提交');
      });

    }

  });

  /**
   * 区域选择
   */
  $(document).on('opened','.popup-area', function () {
    var $areaWrapper = $('#area-wrapper');
    var area = {};
    var areaInfo = '';
    getAreaList();
    function getAreaList(areaId) {
      var areaIdStr = typeof(areaId) == "undefined" ? 0 : areaId;
      areaIdStr = 'area' + areaIdStr;
      var areaList = store.get(areaIdStr);
      if(areaList){
        var html = template('area', areaList);
        $areaWrapper.html(html);
        return false;
      }
      ajax('get','area','area_list',{area_id:areaId},function(ret){
        var area = ret.datas;
        store.set(areaIdStr,area);
        renderHtml($areaWrapper,'area', area);
      });
    }

    $areaWrapper.off('click','li').on('click','li',function () {
      var $this = $(this);
      var areaId = $this.attr('data-area-id');
      var areaDeep = $this.attr('data-area-deep');
      var areaName = $this.attr('data-area-name');
      area['area'+ areaDeep] = areaId;
      //$('#area' + areaDeep).val(areaId);
      areaInfo += areaName;
      if(areaDeep < 3 ){
        getAreaList(areaId);
      }else{
        $.closeModal('.popup-area');
        $('#area1').val(area.area1);
        $('#area2').val(area.area2);
        $('#area3').val(area.area3);
        $('#area-info').text(areaInfo);
        area['areainfo'] = areaInfo;
        store.set('area',area);
      }
    })

  });

  /**
   * 物流信息
   */
  $(document).on('opened','.popup-deliver', function () {
    var orderSn = session.get('order_sn');
    ajax('post','member_order','search_deliver',{order_sn:orderSn},function(ret){
      renderHtml($("#deliver_wrapper"),'deliver', ret.datas);
    });
  });

  $.init();
});
