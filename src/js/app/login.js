/**
 * Created by yefeng on 16/4/7.
 */
require(['jquery','validate'],function ($) {
  //var $ = require('jquery');
  //var validate = require('validate');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    var alarmIcon='<i class="ui-tiptext-icon iconfont iconfont-del"></i>';
    var createCaptchaCode = function(){
      var newpic='index.php?controller=seccode&action=makecode&type=50,120&nchash='+ $(".img-verify").attr("data-hash") +'&t=' + Math.random();
      $(".img-verify").attr("src",newpic);
    };
    $('.jGetCaptcha').click(function(){
      createCaptchaCode();
    });

    $("#jForm").validate({
      onkeyup: false,
      errorElement: "span",
      errorClass: "ui-tiptext ui-tiptext-error",
      errorPlacement:function(error, element) {
        error.appendTo(element.parent())
      },
      highlight: function(element, errorClass, validClass) {
        "radio" !== element.type && $(element).addClass("error").removeClass(validClass)
      },
      unhighlight: function(element, errorClass, validClass) {
        "radio" !== element.type && $(element).addClass(validClass).removeClass("error")
      },
      rules:{
        loginName: {
          required:true,
          minlength:2,
          maxlength:11
        },
        password:{
          required:true,
          minlength:6,
          maxlength:16
        },
        captchaCode : {
          required : true,
          remote:{
            url : 'index.php?controller=seccode&action=check',
            type: 'get',
            data:{
              captcha : function(){
                return $('#captchaCode').val();
              },
              nchash : function(){
                return $(".img-verify").attr("data-hash");
              }
            },
            complete: function(data) {
              if(data.responseText == 'false') {
                createCaptchaCode();
              }
            }
          }
        }

      },
      messages:{
        loginName: {
          required: alarmIcon + "用户名必填",
          minlength: alarmIcon + "用户名最短为2位",
          maxlength: alarmIcon + "用户名最长为11位"
        },
        password:{
          required: alarmIcon + "必须填写密码",
          minlength: alarmIcon + "密码最短为6位",
          maxlength: alarmIcon + "密码最长为16位"
        },
        captchaCode : {
          required : alarmIcon + "验证码不能为空",
          remote	 : alarmIcon + '验证码输入错误'
        }
      },
      submitHandler: function(form) {
        var t = $("#jSubmit");
        t.addClass("btn-disabled").val("正在登录...");
        $.post("index.php?controller=login&action=index2",{
          username:$("#loginName").val(),
          password:$("#password").val(),
          captcha:$('#captchaCode').val(),
          nchash:$(".img-verify").attr("data-hash"),
          formhash:$("#jForm").attr("data-formhash"),
          form_submit:'ok',
          ref_url: t.data('ref-url')
        },function(res){
          console.log(res);
          t.removeClass("btn-disabled").val("登录");
          if(res.code == 200){
            window.location.href = t.data('ref-url');
          }
          if(res.code === 400){
            alert(res.datas.error);
          }
        }, "json");
      }
    })

  }); //DOM ready
});



