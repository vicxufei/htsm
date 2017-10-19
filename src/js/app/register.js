/**
 * Created by yefeng on 16/4/7.
 */

define(function (require) {
  var $ = require('jquery');
  var layer = require('../lib/layer');
  var validate = require('validate');

  //A fabricated API to show interaction of
  //common and specific pieces.
  $(function () {
    layer.config({
      path: 'http://static.htths.com/js/lib/'
    });
    var alarmIcon='<i class="ui-tiptext-icon iconfont iconfont-del"></i>';
    var createCaptchaCode = function(){
      var newpic= 'index.php?controller=seccode&action=makecode&type=50,120&nchash='+ $(".img-verify").attr("data-hash") +'&t=' + Math.random();
      $(".img-verify").attr("src",newpic);
    };
    $('.jGetCaptcha').click(function(){
      createCaptchaCode();
    });
    $('#jSendMsg').click(function(){
      $.getJSON("index.php?controller=connect_sms&action=get_captcha", {
        nchash:1,
        type: 1,
        phone: $('#phone').val()
      }, function (data) {
        //if (data) {
        //  console.log(data);
        //  //$.sDialog({skin: "green", content: "发送成功", okBtn: false, cancelBtn: false});
        //  //$(".code-again").hide();
        //  //$(".code-countdown").show().find("em").html(e.datas.sms_time);
        //  //var c = setInterval(function () {
        //  //  var e = $(".code-countdown").find("em");
        //  //  var a = parseInt(e.html() - 1);
        //  //  if (a == 0) {
        //  //    $(".code-again").show();
        //  //    $(".code-countdown").hide();
        //  //    clearInterval(c)
        //  //  } else {
        //  //    e.html(a)
        //  //  }
        //  //}, 1e3)
        //} else {
        //  createCaptchaCode();
        //  errorTipsShow("<p>" + data.datas.error + "<p>")
        //}
        if(data.code == 400){
        layer.msg(data.datas.error)
        }
        if(data.code == 200){
          console.log(data);
          layer.msg(data.datas);
        }






      })
    });

    // 手机号码验证
    jQuery.validator.addMethod("isPhone", function(value, element) {
      var length = value.length;
      //正则判断手机号
      return this.optional(element) || (length == 11 && /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test(value));
    }, "请正确填写您的手机号码。");
    // 密码验证
    jQuery.validator.addMethod("passwd", function(value, element) {
      return this.optional(element) || /^[A-Za-z0-9_\.]+$/.test(value);
    }, "格式不正确,应由英文字母、数字,下划线 和小数点组成！");

    jQuery.validator.addMethod("checkpwdhard", function( value, element ) {
      var str = value;
      var result = false;

      if(str.length > 0){
        var patt = /^[a-zA-Z0-9]{6,20}$/;
        var result1 = patt.test(str);
        //先測試是否有英文
        var pattEN = /[a-zA-Z]{1,}/;
        result2 = pattEN.test(str);
        //先測試是否有數字
        var pattDigit = /[0-9]{1,}/;
        result3 = pattDigit.test(str);

        if(result1 == true && result2 == true && result3 == true){
          result = true;
        } else{
          result = false;
        }
      } else {
        result = true;
      }
      return result;
    }, "密码由6-20个数字和字母的组合");


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
        phone: {
          isPhone : true
        },
        password:{
          required:true,
          minlength:6,
          maxlength:20,
          checkpwdhard :true
        },
        password1 : {
          required : true,
          equalTo  : '#password'
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
        },
        register_captcha : {
          required : true,
          remote:{
            url : 'index.php?controller=connect_sms&action=check_captcha',
            type: 'get',
            data:{
              phone : function(){
                return $('#phone').val();
              },
              sms_captcha : function(){
                return $("#register_captcha").val();
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
        password:{
          required: alarmIcon + "必须填写密码",
          minlength: alarmIcon + "密码最短为6位",
          maxlength: alarmIcon + "密码最长为20位"
        },
        password1:{
          required: alarmIcon + "请填写密码",
          equalTo: alarmIcon + "两次输入的密码不一致"
        },
        captchaCode : {
          required : alarmIcon + "验证码不能为空",
          remote	 : alarmIcon + '验证码输入错误'
        }
      },
      submitHandler: function(form) {
        console.log($("#jForm").serialize());
        var submitButton = $("#jSubmit");
        submitButton.addClass("btn-disabled").val("提交中...");
        $.post(memberUrl + "controller=connect_sms&action=register", $("#jForm").serialize(),function(res){
          submitButton.removeClass("btn-disabled").val("同意协议并注册");
          if(res.code == 200){
            var refUrl = $('#ref-url').val();
            if(refUrl){
              window.location.href = refUrl;
            }else {
              window.location.href = 'http://www.htths.com';
            }
          }
          if(res.code === 400){
            layer.msg(data.datas.error);
          }
        }, "json");
      }
    })

  }); //DOM ready
});



