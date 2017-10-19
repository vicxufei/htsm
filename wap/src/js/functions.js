/**
 * Created by yefeng on 16/6/23.
 */
// var mobile_url = 'http://api.htths.com/index.php?XDEBUG_SESSION_START=PHPSTORM&';
var mobile_url = 'http://api.htths.com/index.php?';
function ajax(requertType,controller,action,data,callback,error){
  var targetUrl = mobile_url + '&controller=' + controller;
  if(action){
    targetUrl += '&action=' + action;
  }
  if (!data) {
    data={};
  }

  var arr = [
    'goods_detail'//商品详情
  ];


  var requestType = requertType.toUpperCase();
  if(requestType == 'POST' || $.inArray(action, arr) != -1){
    var token = store.get('token');
    if(typeof token == 'object'){
      data['key'] = token.key;
    }
  }

// console.log(data);
  $.ajax({
    url : targetUrl,
    type : requertType,
    data : data,
    dataType : 'json',
    success : function (ret) {
      // console.log(ret);
      $.hideIndicator();
      if (typeof ret != 'object') {
        ret = {code:404,datas:{error:'当前网络状况不佳,请稍后重试'}};
      }
      if (ret.code == 200){
        callback(ret);
      }else if(ret.datas.error =='请登录') {
        $.router.load('#login');
      }else{
        if(!ret.no_deviceid){
          $.toast(ret.datas.error);
        }
        if (typeof error == "function"){
          error(ret);
        }
      }
    },
    error: function(xhr, type){
      $.hideIndicator();
      $.alert('请检测网络连接!')
    }

  })

}

// 判断 {} 非空 空返回true
function isEmpty(value) {
  if (!value) return true;
  return Object.keys(value).length === 0;
}

// 过滤空数据
function checkData(data){
  if (data == 'null' || data == null){
    return '';
  }
  return data;
}

// function checkLogin() {
//   if(!store.get('token')) {
//     $.router.load('#login');
//     return false;
//   }else{
//     return true;
//   }
// }

function renderHtml($node,tpl,data,append) {
  var html = template(tpl, data);
  if(append){
    $node.append(html);
  }else {
    $node.html(html);
  }
}

function redirect(type,param){

  if (typeof type == 'object') {
    var param = $(type).data('param');
    var type = $(type).data('type');
  }

  switch (type) {
    case 'good':
      _goods(param);
      break;
    case 'goods':
      __goodslist(param)
      break;
    default:
      break;
  }
}

var session = {
  set: function (key,data) {
    if(typeof data != 'string'){
      sessionStorage.setItem(key, JSON.stringify(data));
    }else{
      sessionStorage.setItem(key, data);
    }
  },
  get: function (key) {
    var data = sessionStorage.getItem(key);
    if(data){
      data = JSON.parse(data);
    }
    return data;
  }
};

function checkMobile(phoneNumber) {
  //验证手机号
  var reg =  /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/;
  var ret = reg.test(phoneNumber);
  if(!phoneNumber){
    $.alert('请输入手机号');
    return false;
  }else if(!ret){
    $.alert('请输入正确的手机号');
    return false;
  }else{
    return true;
  }
}


