/**
 * Created by yefeng on 16/5/3.
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
    var memberUrl = 'http://i.htths.com/index.php?';
    var jAddress = $('#jAddress');
    var renderHtml = function(data,tplId,wrapElement){
      if(data.code==200){
        var html = template(tplId, data.datas);
        $(wrapElement).html(html);
      }
      if(data.code==400){
        layer.msg(data.datas.error);
      }
    };


//获取收货地址列表
    var getAddressList = function(){
      var index = layer.load(0, {shade: false});
      $.ajax({
        type: "get",
        url: memberUrl + 'controller=member_address&action=address_ajax',
        //data: {id: address_id},
        dataType: "json",
        success: function (data) {
          console.log(data);
          layer.close(index);
          //renderAddress(data);
          renderHtml(data,'tpl-member-address','#jAddress');
        }
      })
    };
    getAddressList();

//删除收货地址
    jAddress.on('click','.jDeleteAddr',function(e){
      var addressId = $(this).parents('ul').data('addrid');
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
            url: memberUrl + 'controller=member_address&action=address_ajax',
            data: {id: addressId},
            dataType: "json",
            success: function (data) {
              layer.close(index);
              renderHtml(data,'tpl-member-address','#jAddress');
            }
          })
        } //yes
      }); //layer.msg
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
        submitHandler:function(form){
          //form.submit();

          $.post(memberUrl + "controller=member_address&action=address_ajax",objForm.serialize(),function(data){
            //console.log(data);
            layer.close(objLayer);
            renderHtml(data,'tpl-member-address','#jAddress');
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
    jAddress.on('click','.jAddAddr',function(){
      addressSubmit({});
    });

    //编辑收货地址
    jAddress.on('click','.jEditAddr',function(){
      var addressData = {
        'address_id' : $(this).parents('ul').data('addrid'),
        'true_name' : $(this).data('true-name'),
        'pr_id' : $(this).data('pr-id'),
        'city_id' : $(this).data('city-id'),
        'area_id' : $(this).data('area-id'),
        'area_info' : $(this).data('area-info'),
        'address' : $(this).data('address'),
        'mob_phone' : $(this).data('mob-phone')
      };
      addressSubmit(addressData);
    });


    $('#jSubBtn').click(function(){
      $('#jSubForm').submit()

    });

  }); //DOM ready
});

