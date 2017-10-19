/**
 * Created by yefeng on 16/4/23.
 */
/**
 * Created by yefeng on 16/4/23.
 */
define(function (require) {
  var $ = require('jquery');
  var template = require('template');
  var layer = require('../lib/layer');
  var header = require('header');
  var region = require('region');
  var validate = require('validate');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    //start
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var shopUrl = 'http://www.htths.com/index.php?';
    var apiUrl = 'http://api.htths.com/index.php?';
    var jSubBtn = $("#jSubBtn");
    var renderHtml = function(data,tplId,wrapElement){
      if(data.code==200){
        var html = template(tplId, data.datas);
        $(wrapElement).html(html);
      }
      if(data.code==400){
        layer.msg(data.datas.error);
      }
    };

    //门店列表
    var jChainList = $("#jChainList");
    if(jChainList.data("if-chain")){
      var chainList = function(){
        $.getJSON(apiUrl + 'controller=area&action=chain_list',function(data){
          renderHtml(data,'tpl-chain-list','#jChainList');
        });
      };
      chainList();
    }
    //门店切换
    jChainList.on('click','.jChainItem',function(){
      var _this = $(this);
      jChainList.find("input[type='radio'][name='chain_id']").prop('checked', false);
      _this.siblings().removeClass("item-selected");
      _this.find('input:radio').prop('checked', true);
      _this.addClass("item-selected");
    });

    var jItemWrap = $('.jItemWrap');
    //获取收货地址列表
    var getAddressList = function(){
      var index = layer.load(0, {shade: false});
      $.ajax({
        type: "get",
        url: shopUrl + 'controller=buy&action=load_addr',
        //data: {id: address_id},
        dataType: "json",
        success: function (data) {
          layer.close(index);
          //renderAddress(data);
          renderHtml(data,'tpl-address-list','.jItemWrap');
        }
      })
    };
    if($('#ifChain').val() != 1){
   //   getAddressList();
    }
    //getBuyGoods();

    //移到收货地址上显示编辑和删除按钮
    jItemWrap.on('mouseover','.jAddrListItem',function(){
      $(this).addClass('item-hover');
    });
    jItemWrap.on('mouseout','.jAddrListItem',function(){
        $(this).removeClass('item-hover');
    });

    var getBuyGoods = function(){
      var index = layer.load(0, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
      });
      $.ajax({
        type: "post",
        url: shopUrl + 'controller=buy&action=buy',
        data: $('#jSubForm').serialize(),
        dataType: "json",
        success: function (data) {
          layer.close(index);
          console.log(data);
          if(data.code == 200){
            $('#jTotalMoney').text(data.datas.need2pay);
            jSubBtn.data("allow-submit",data.datas.allow_submit);
            jSubBtn.data("tips",data.datas.error_message);
            jSubBtn.data("need-idcard",data.datas.need_idcard);
            renderHtml(data,'tpl-goods-list','.jTableWrap');

          }
        }
      });
    };

    //删除收货地址
    jItemWrap.on('click','.jOperDelete',function(e){
      var address_id = $(this).parent().data('addrid');
      var ex=e || event;
      var offsetTop = ex.clientY + 'px';
      var offsetLeft = ex.clientX + 'px';
      layer.msg('确定删除吗,删除后不可以恢复哦？', {
        time: 0 //不自动关闭
        ,btn: ['确定', '取消']
        ,offset: [offsetTop,offsetLeft]
        ,yes: function(index){
          $.ajax({
            type: "get",
            url: shopUrl + 'controller=buy&action=load_addr',
            data: {id: address_id},
            dataType: "json",
            success: function (data) {
              layer.close(index);
              renderHtml(data,'tpl-address-list','.jItemWrap');
              getBuyGoods();
            }
          })
        } //yes
      }); //layer.msg
      return false;  //防止冒泡
    });

    //切换收货地址
    var checkAllowChange = function(addressId){
      var isAllow = true;
      $("#jProductlist").find("table.jGoodItem").each(function(){
        var supportedAreaid = $(this).data("supported-areaids");
        if(supportedAreaid.indexOf(','+addressId + ',') < 0){
          isAllow = false;
        }
      });
      return isAllow;
    };
    jItemWrap.on('click','.jAddrListItem',function(){
      var _this = $(this);
      var areaId = _this.data("area-id");
      if(checkAllowChange(areaId)){
        jItemWrap.find("input[type='radio'][name='address_id']").prop('checked', false);
        jItemWrap.find('.jAddrListItem').removeClass('item-selected');
        _this.addClass('item-selected');
        _this.find('input:radio').prop('checked', true);
        getBuyGoods();

        //身份证信息切换
        if(jSubBtn.data("need-idcard") > 0){
          var addressID = _this.data("addrid");
          var element = _this.find("a.jOperEdit");
          var tureName = element.data("true-name");
          var idcardNo = element.data("idcard-no");
          $("#jRealName").text(tureName);
          $("#jIdCard").text(idcardNo);
          var idcardInput = $("#jModInput");
          idcardInput.data("address-id",addressID);
          idcardInput.val(idcardNo);
        }

      }else{
        layer.alert('有商品不支持配送到您选择的收货地址', {icon: 6});
      }
    });


    //收货地址-表单验证提交
    var addressSubmit = function(data){
      var html = template('tpl-address-add',data);
      var objLayer=layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['680px', '384px'], //宽高
        content: html
      });
      //三级地区选择
      $("#region").nc_region();
      var objForm = $('#jAddAddrForm');
      $("#jSaveBtn").click(function(){
        objForm.submit();
      });
      $("#jCancelBtn").click(function(){
        layer.close(objLayer);
      });
      //表单验证
      objForm.validate({
        submitHandler:function(){
          $.post(shopUrl + "controller=buy&action=add_addr",objForm.serialize(),function(data){
            if(data.code == 200){
              layer.close(objLayer);
              renderHtml(data,'tpl-address-list','.jItemWrap');
              getBuyGoods();
            }else {
              layer.msg(data.datas.error);
            }
          }, "json");


        },
        errorElement: "label",
        errorClass: "error",
        invalidHandler: function(form, validator) {
          var errors = validator.numberOfInvalids();
          if(errors)
          {
            $('#warning').show();
          }
          else
          {
            $('#warning').hide();
          }
        },
        rules : {
          true_name : {
            required : true
          },
          address : {
            required : true
          },
          region : {
            checklast: true
          },
          mob_phone : {
            required : true,
            //         required : check_phone,
            minlength : 11,
            maxlength : 11,
            digits : true
          }
        },
        messages : {
          true_name : {
            required : 'member_address_input_receiver'
          },
          address : {
            required : 'member_address_input_address'
          },
          mob_phone : {
            required : 'member_address_phone_and_mobile',
            minlength: 'member_address_wrong_mobile',
            maxlength: 'member_address_wrong_mobile',
            digits : 'member_address_wrong_mobile'
          }
        }
      });
    };
    //添加收货地址
    $('#jUseNewAddrChk').click(function(){
      addressSubmit({});
    });

    //编辑收货地址
    jItemWrap.on('click','.jOperEdit',function(){
      var addressData = {
        'address_id' : $(this).parent().data('addrid'),
        'true_name' : $(this).data('true-name'),
        'pr_id' : $(this).data('pr-id'),
        'city_id' : $(this).data('city-id'),
        'area_id' : $(this).data('area-id'),
        'area_info' : $(this).data('area-info'),
        'address' : $(this).data('address'),
        'mob_phone' : $(this).data('mob-phone')
      };
      addressSubmit(addressData);
      return false;  //防止冒泡
    });

    //修改身份证号码按钮
    $("#jAModify").click(function(){
      $(".jIdShowBox").hide();
      $(".jIdModifyBox").show();
    });
    //确定按钮修改身份证号码
    $("#jSureMod").click(function(){
      var idcardInput = $("#jModInput");
      var addressId = idcardInput.data("address-id");
      var idcardNO = idcardInput.val();
      $.post(shopUrl + "controller=buy&action=idcard_edit",{
        address_id : addressId,
        idcard_no : idcardNO
      },function(data){
        if(data.code == 200){
          $("#jIdCard").text(data.datas.idcard_no);
          $(".jIdShowBox").show();
          $(".jIdModifyBox").hide();
          getBuyGoods();
        }else {
          layer.msg(data.datas.error);
        }
      }, "json");


    });


    //提交生成订单
    jSubBtn.click(function(){
      var _this = $(this);

      if(_this.data("allow-submit")){
        $.ajax({
          type: "post",
          url: shopUrl + "controller=buy&action=buy_step2",
          data: $('#jSubForm').serialize(),
          dataType: "json",
          success: function (data) {
            if(data.code == 400){
              layer.msg(data.datas.error);
            }
            if(data.code == 200){
              window.location.href = shopUrl + 'controller=buy&action=pay&pay_sn=' +data.datas.pay_sn;
            }
          }
        })
      }else{
        layer.alert(_this.data("tips"), {icon: 6});
      }

    });

  }); //DOM ready
});

