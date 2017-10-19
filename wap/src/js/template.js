/*TMODJS:{"version":"1.0.0"}*/
!function () {

    function template (filename, content) {
        return (
            /string|function/.test(typeof content)
            ? compile : renderFile
        )(filename, content);
    };


    var cache = template.cache = {};
    var String = this.String;

    function toString (value, type) {

        if (typeof value !== 'string') {

            type = typeof value;
            if (type === 'number') {
                value += '';
            } else if (type === 'function') {
                value = toString(value.call(value));
            } else {
                value = '';
            }
        }

        return value;

    };


    var escapeMap = {
        "<": "&#60;",
        ">": "&#62;",
        '"': "&#34;",
        "'": "&#39;",
        "&": "&#38;"
    };


    function escapeFn (s) {
        return escapeMap[s];
    }


    function escapeHTML (content) {
        return toString(content)
        .replace(/&(?![\w#]+;)|[<>"']/g, escapeFn);
    };


    var isArray = Array.isArray || function(obj) {
        return ({}).toString.call(obj) === '[object Array]';
    };


    function each (data, callback) {
        if (isArray(data)) {
            for (var i = 0, len = data.length; i < len; i++) {
                callback.call(data, data[i], i, data);
            }
        } else {
            for (i in data) {
                callback.call(data, data[i], i);
            }
        }
    };


    function resolve (from, to) {
        var DOUBLE_DOT_RE = /(\/)[^/]+\1\.\.\1/;
        var dirname = ('./' + from).replace(/[^/]+$/, "");
        var filename = dirname + to;
        filename = filename.replace(/\/\.\//g, "/");
        while (filename.match(DOUBLE_DOT_RE)) {
            filename = filename.replace(DOUBLE_DOT_RE, "/");
        }
        return filename;
    };


    var utils = template.utils = {

        $helpers: {},

        $include: function (filename, data, from) {
            filename = resolve(from, filename);
            return renderFile(filename, data);
        },

        $string: toString,

        $escape: escapeHTML,

        $each: each
        
    };


    var helpers = template.helpers = utils.$helpers;


    function renderFile (filename, data) {
        var fn = template.get(filename) || showDebugInfo({
            filename: filename,
            name: 'Render Error',
            message: 'Template not found'
        });
        return data ? fn(data) : fn; 
    };


    function compile (filename, fn) {

        if (typeof fn === 'string') {
            var string = fn;
            fn = function () {
                return new String(string);
            };
        }

        var render = cache[filename] = function (data) {
            try {
                return new fn(data, filename) + '';
            } catch (e) {
                return showDebugInfo(e)();
            }
        };

        render.prototype = fn.prototype = utils;
        render.toString = function () {
            return fn + '';
        };

        return render;
    };


    function showDebugInfo (e) {

        var type = "{Template Error}";
        var message = e.stack || '';

        if (message) {
            // 利用报错堆栈信息
            message = message.split('\n').slice(0,2).join('\n');
        } else {
            // 调试版本，直接给出模板语句行
            for (var name in e) {
                message += "<" + name + ">\n" + e[name] + "\n\n";
            }  
        }

        return function () {
            if (typeof console === "object") {
                console.error(type + "\n\n" + message);
            }
            return type;
        };
    };


    template.get = function (filename) {
        return cache[filename.replace(/^\.\//, '')];
    };


    template.helper = function (name, helper) {
        helpers[name] = helper;
    };


    if (typeof define === 'function') {define(function() {return template;});} else if (typeof exports !== 'undefined') {module.exports = template;} else {this.template = template;}
    
    /*v:1*/
template('addr-edit',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$escape=$utils.$escape,address_info=$data.address_info,$out='';$out+='<input type="hidden" name="address_id" id="address-id" value="';
$out+=$escape(address_info.address_id);
$out+='"/> <input type="hidden" name="pr_id" id="area1" value="';
$out+=$escape(address_info.pr_id);
$out+='"/> <input type="hidden" name="city_id" id="area2" value="';
$out+=$escape(address_info.city_id);
$out+='"/> <input type="hidden" name="area_id" id="area3" value="';
$out+=$escape(address_info.area_id);
$out+='"/> <li> <a href="#" class="item-link item-content open-popup" data-popup=".popup-area"> <div class="item-inner"> <div class="item-title">地区选择</div> <div class="item-after" id="area-info">';
$out+=$escape(address_info.area_info);
$out+='</div> </div> </a> </li> <li> <div class="item-content"> <div class="item-inner"> <div class="item-title label">详细地址</div> <div class="item-input"> <input type="text" placeholder="请输入详细地址" id="addr-detail" value="';
$out+=$escape(address_info.address);
$out+='"> </div> </div> </div> </li> <li> <div class="item-content"> <div class="item-inner"> <div class="item-title label">收 件 人</div> <div class="item-input"> <input type="text" placeholder="请输入收货人姓名" id="true-name" value="';
$out+=$escape(address_info.true_name);
$out+='"> </div> </div> </div> </li> <li> <div class="item-content"> <div class="item-inner"> <div class="item-title label">手 机 号</div> <div class="item-input"> <input type="text" placeholder="请输入收货人手机号" id="mob-phone" value="';
$out+=$escape(address_info.mob_phone);
$out+='"> </div> </div> </div> </li> <li> <div class="item-content"> <div class="item-inner"> <div class="item-title label">身份证号码</div> <div class="item-input"> <input type="text" placeholder="购保税仓商品必填" id="idcard_no" value="';
$out+=$escape(address_info.idcard_no);
$out+='"> </div> </div> </div> </li>';
return new String($out);
});/*v:1*/
template('address',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$escape=$utils.$escape,address_count=$data.address_count,$each=$utils.$each,address_list=$data.address_list,value=$data.value,i=$data.i,$out='';$out+='<div class="content-padded">已有<span class="button-danger" id="address-count">';
$out+=$escape(address_count);
$out+='</span>条收货地址（最多添加10条）</div> ';
$each(address_list,function(value,i){
$out+=' <div class="card" data-address-id="';
$out+=$escape(value['address_id']);
$out+='"> <div class="card-content"> <div class="card-content-inner address-item"> <p>';
$out+=$escape(value['true_name']);
$out+=' ';
$out+=$escape(value['mob_phone']);
$out+='</p> <p>';
$out+=$escape(value['area_info']);
$out+=' ';
$out+=$escape(value['address']);
$out+='</p> ';
if(value['idcard_no']){
$out+='<p>身份证号:';
$out+=$escape(value['idcard_no']);
$out+='</p>';
}
$out+=' </div> </div> <div class="card-footer"> <div class="op-btn"> <a class="edit" href=""><i class="icon iconfont icon-edit"></i>编辑</a> <a class="del" href=""><i class="icon iconfont icon-delete"></i>删除</a> <a class="default" href=""><i class="icon iconfont ';
if(value.is_default == 1){
$out+='icon-roundcheckfill';
}else{
$out+='icon-roundcheck';
}
$out+='"></i>设为默认</a> </div> </div> </div> ';
});
$out+=' <div class="content-padded"> <a class="button button-fill button-warning" id="add-address">添加收货地址</a> </div>';
return new String($out);
});/*v:1*/
template('area',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,area_list=$data.area_list,value=$data.value,i=$data.i,$escape=$utils.$escape,$out='';$each(area_list,function(value,i){
$out+=' <li class="item-content ';
if(value.area_deep != 3){
$out+='item-link';
}
$out+='" data-area-id="';
$out+=$escape(value.area_id);
$out+='" data-area-deep="';
$out+=$escape(value.area_deep);
$out+='" data-area-name="';
$out+=$escape(value.area_name);
$out+='"> <div class="item-inner"> <div class="item-title">';
$out+=$escape(value.area_name);
$out+='</div> </div> </li> ';
});
return new String($out);
});/*v:1*/
template('buy',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$escape=$utils.$escape,deliveryText=$data.deliveryText,selected_address_info=$data.selected_address_info,error_message=$data.error_message,$each=$utils.$each,store_cart_list=$data.store_cart_list,value=$data.value,i=$data.i,store_cart_total=$data.store_cart_total,freight_total=$data.freight_total,$out='';$out+='<div class="list-block mb10"> <a href="" class="item-link item-content" id="delivery"> <div class="item-inner"> <div class="item-title">配送方式</div> <div class="item-after" id="delivery-text">';
$out+=$escape(deliveryText);
$out+='</div> </div> </a> </div> ';
if(selected_address_info){
$out+=' <div class="list-block media-list"> <ul> <li> <a href="#address" data-no-history="true" class="item-link item-content"> <div class="item-inner"> <div class="item-title-row"> <div class="item-title">';
$out+=$escape(selected_address_info['true_name']);
$out+='-';
$out+=$escape(selected_address_info['mob_phone']);
$out+='</div> </div> <div class="item-subtitle">';
$out+=$escape(selected_address_info['area_info']);
$out+='</div> <div class="item-text">';
$out+=$escape(selected_address_info['address']);
$out+='</div> </div> </a> </li> </ul> </div> ';
}else{
$out+=' <div class="content-block"> <a href="#address" class="button button-fill button-danger">添加收货地址</a> </div> ';
}
$out+=' ';
if(error_message){
$out+=' <div class="list-block"> <li class="item-content f14 color-danger">提示信息:';
$out+=$escape(error_message);
$out+='</li> </div> ';
}
$out+=' <div class="list-block media-list mt10"> <ul class="list-container goods-list"> ';
$each(store_cart_list,function(value,i){
$out+=' <li> <a href="" data-good_id="';
$out+=$escape(value.goods_id);
$out+='" class="item-content jGo"> <div class="item-media"> <img src="';
$out+=$escape(value.goods_image_url);
$out+='"> </div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title f14">';
$out+=$escape(value.goods_name);
$out+='</div> </div> <div class="item-subtitle"> <span class="present color-danger f13 mr10">¥';
$out+=$escape(value.goods_price);
$out+='</span> <span class="original f10 color-gray"><del>¥';
$out+=$escape(value.goods_marketprice);
$out+='</del></span> </div> <div class="item-text">';
if(value.is_chain){
$out+='支持自提';
}
$out+=' <span class="badge pull-right">x';
$out+=$escape(value.goods_num);
$out+='</span> </div> </div> </a> </li> ';
});
$out+=' </ul> </div> <div class="list-block"> <ul> <li class="item-content"> <div class="item-inner"> <div class="item-title">商品总计</div> <div class="item-after">¥';
$out+=$escape(store_cart_total);
$out+='</div> </div> </li> <li class="item-content"> <div class="item-inner"> <div class="item-title">运费总计</div> <div class="item-after">¥';
$out+=$escape(freight_total);
$out+='</div> </div> </li> </ul> </div>';
return new String($out);
});/*v:1*/
template('cart',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,cart_num=$data.cart_num,$each=$utils.$each,cart_list=$data.cart_list,value=$data.value,i=$data.i,$escape=$utils.$escape,$out='';if(!cart_num){
$out+=' <div class="empty"> <p class="emt-img"></p> <p class="emt-txt">购物车快饿瘪了T.T</p> <p class="emt-txt-tag">主人快给我挑点宝贝吧</p> <a href="#home" class="button button-small" >去逛逛</a> </div> ';
}else{
$out+=' ';
$each(cart_list,function(value,i){
$out+=' <li class="item-content"> <div class="item-media radio" data-cart_id="';
$out+=$escape(value.cart_id);
$out+='" data-checked="';
$out+=$escape(value.cart_checked);
$out+='"> <i class="icon iconfont ';
if(value.cart_checked== 1){
$out+='icon-roundcheckfill';
}else{
$out+='icon-roundcheck';
}
$out+='"></i> </div> <div class="item-media"> <a href="" data-good_id="';
$out+=$escape(value.goods_id);
$out+='" class="jGo"> <img src="';
$out+=$escape(value.goods_image);
$out+='"> </a> </div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title f12"> <a href="" data-good_id="';
$out+=$escape(value.goods_id);
$out+='" class="jGo">';
$out+=$escape(value.goods_name);
$out+='</a> </div> </div> <div class="item-subtitle"> <div class="add-box jQty"> <a class="box-zone-l ';
if(value.goods_num > 1){
$out+='jQtyMin';
}else{
$out+='jDel';
}
$out+='" data-num="';
$out+=$escape(value.goods_num);
$out+='" data-cart_id="';
$out+=$escape(value.cart_id);
$out+='"> <div class="box-l box-del"> <i class="icon iconfont ';
if(value.goods_num > 1){
$out+='icon-move';
}else{
$out+='icon-delete';
}
$out+='"></i> </div> </a> <div class="box-zone-m"> <div class="box-m jQtyTxt">';
$out+=$escape(value.goods_num);
$out+='</div> </div> <a class="box-zone-r jQtyAdd" data-num="';
$out+=$escape(value.goods_num);
$out+='" data-cart_id="';
$out+=$escape(value.cart_id);
$out+='"> <div class="box-r"> <i class="icon iconfont icon-add"></i> </div> </a> </div> </div> <div class="item-text"> <span class="present color-danger f13 mr10">¥';
$out+=$escape(value.good_final_price);
$out+='</span> <span class="original f10 color-gray"><del>¥';
$out+=$escape(value.goods_marketprice);
$out+='</del></span> </div> </div> </li> ';
});
$out+=' ';
}
return new String($out);
});/*v:1*/
template('catlog',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,class_list=$data.class_list,value=$data.value,i=$data.i,$escape=$utils.$escape,cValue=$data.cValue,$out='';$each(class_list,function(value,i){
$out+=' <dl> <dt><a href="" data-gcid="';
$out+=$escape(value.gc_id);
$out+='">';
$out+=$escape(value.gc_name);
$out+='</a></dt> ';
$each(value.child,function(cValue,i){
$out+=' <dd><a href="" data-gcid="';
$out+=$escape(cValue.gc_id);
$out+='">';
$out+=$escape(cValue.gc_name);
$out+='</a> </dd> ';
});
$out+=' </dl> ';
});
return new String($out);
});/*v:1*/
template('comment',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,goods_eval_list=$data.goods_eval_list,$each=$utils.$each,value=$data.value,i=$data.i,$escape=$utils.$escape,$out='';$out+='<div class="list-block media-list"> ';
if(goods_eval_list.length == 0){
$out+=' <div class="empty"> <p class="icon iconfont icon-form f14"></p> <p class="emt-txt">暂无评论</p> </div> ';
}else{
$out+=' <ul> ';
$each(goods_eval_list,function(value,i){
$out+=' <li> <div class="item-content"> <div class="item-media"><img src="';
$out+=$escape(value.member_avatar);
$out+='"></div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title">';
$out+=$escape(value.geval_frommembername);
$out+='</div> <div class="item-after">';
$out+=$escape(value.add_time);
$out+='</div> </div> <div class="item-subtitle">';
$out+=$escape(value.geval_content);
$out+='</div> </div> </div> </li> ';
});
$out+=' </ul> ';
}
$out+=' </div>';
return new String($out);
});/*v:1*/
template('deliver',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$escape=$utils.$escape,express_name=$data.express_name,shipping_code=$data.shipping_code,$each=$utils.$each,deliver_info=$data.deliver_info,value=$data.value,i=$data.i,$out='';$out+='<div>物流公司:';
$out+=$escape(express_name);
$out+='</div> <div>物流单号:';
$out+=$escape(shipping_code);
$out+='</div> ';
$each(deliver_info,function(value,i){
$out+=' <div>';
$out+=$escape(value);
$out+='</div> ';
});
return new String($out);
});/*v:1*/
template('favorite',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,favorites_list=$data.favorites_list,$each=$utils.$each,value=$data.value,i=$data.i,$escape=$utils.$escape,$out='';if(!favorites_list){
$out+=' <div class="empty"> <p class="icon iconfont icon-form f14"></p> <p class="emt-txt">暂无评论</p> </div> ';
}else{
$out+=' ';
$each(favorites_list,function(value,i){
$out+=' <li> <a href="" data-good_id="';
$out+=$escape(value.goods_id);
$out+='" class="item-content jGo"> <div class="item-media"> <img class="b-lazy" data-src="';
$out+=$escape(value.goods_image_url);
$out+='" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="></div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title f12">';
$out+=$escape(value.goods_name);
$out+='</div> </div> <div class="item-text"> <span class="present color-danger f13 mr10">¥';
$out+=$escape(value.goods_price);
$out+='</span> </div> </div> </a> </li> ';
});
$out+=' ';
}
return new String($out);
});/*v:1*/
template('good-basic',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,slide=$data.slide,value=$data.value,i=$data.i,$escape=$utils.$escape,goods_info=$data.goods_info,is_favorate=$data.is_favorate,freight_title=$data.freight_title,freight_to=$data.freight_to,freight_text=$data.freight_text,specValue=$data.specValue,x=$data.x,spec_list=$data.spec_list,$out='';$out+='<div class="swiper-container" id="slide" data-space-between=\'10\'> <div class="swiper-wrapper"> ';
$each(slide,function(value,i){
$out+=' <div class="swiper-slide"> <img src="';
$out+=$escape(value);
$out+='" alt=""> </div> ';
});
$out+=' </div> <div class="swiper-pagination"></div> </div> <div class="item-prices"> <span class="item-price show-price-tag" data-text="促销价">';
$out+=$escape(goods_info.goods_price);
$out+='</span> <span class="item-price-origin">';
$out+=$escape(goods_info.goods_marketprice);
$out+='</span> <span class="pull-right"> <i id="favorate" class="icon iconfont icon-like ';
$out+=$escape(is_favorate ? 'active':'');
$out+='"></i> </span> </div> <div class="item-info"> <div class="item-title">';
$out+=$escape(goods_info.goods_name);
$out+='</div> <div class="item-desc"> ';
$out+=$escape(goods_info.goods_jingle);
$out+=' </div> </div> <div class="freight-info" id="freight-info"> <p>配送:';
$out+=$escape(freight_title);
$out+='</p> <p>送至:';
$out+=$escape(freight_to);
$out+=',运费';
$out+=$escape(freight_text);
$out+='</p> </div> ';
if(goods_info.spec_name){
$out+=' <div class="good-spec"> ';
$each(goods_info.spec_name,function(value,i){
$out+=' <div class="content-block-title">';
$out+=$escape(value);
$out+='</div> <div class="spec-item goods-list"> ';
$each(goods_info.spec_value[i],function(specValue,x){
$out+=' <a href="" data-good_id="';
$out+=$escape(spec_list[x]);
$out+='" class="';
if(goods_info.goods_spec[x]){
$out+='active';
}
$out+='">';
$out+=$escape(specValue);
$out+='</a> ';
});
$out+=' </div> ';
});
$out+=' </div> ';
}
return new String($out);
});/*v:1*/
template('goods',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,goods_list=$data.goods_list,value=$data.value,i=$data.i,$escape=$utils.$escape,$out='';$each(goods_list,function(value,i){
$out+=' <li> <a href="" data-good_id="';
$out+=$escape(value.goods_id);
$out+='" class="item-content jGo"> <div class="item-media"> <img class="b-lazy" data-src="';
$out+=$escape(value.goods_image_url);
$out+='" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="></div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title f12">';
$out+=$escape(value.goods_name);
$out+='</div> </div> <div class="item-subtitle">';
$out+=$escape(value.goods_jingle);
$out+='</div> <div class="item-text"> <span class="present color-danger f13 mr10">¥';
$out+=$escape(value.goods_price);
$out+='</span> <span class="original f10 color-gray"><del>¥';
$out+=$escape(value.goods_marketprice);
$out+='</del></span> </div> </div> </a> </li> ';
});
return new String($out);
});/*v:1*/
template('my-order',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,order_list=$data.order_list,$each=$utils.$each,value=$data.value,i=$data.i,$escape=$utils.$escape,good=$data.good,j=$data.j,hasmore=$data.hasmore,$out='';if(order_list.length == 0){
$out+=' <div class="empty"> <p class="icon iconfont icon-form f14"></p> <p class="emt-txt">暂无订单</p> </div> ';
}else{
$out+=' ';
$each(order_list,function(value,i){
$out+=' <div class="card"> <div class="card-header">订单号:';
$out+=$escape(value.order_sn);
$out+='<span class="pull-right">';
$out+=$escape(value.add_time);
$out+='</span></div> <div class="card-content" data-order_id="';
$out+=$escape(value.order_id);
$out+='"> <div class="list-block media-list"> <ul> ';
$each(value.extend_order_goods,function(good,j){
$out+=' <li class="item-content"> <div class="item-media"> <img src="';
$out+=$escape(good.goods_image);
$out+='"> </div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title">';
$out+=$escape(good.goods_name);
$out+='</div> </div> <div class="item-subtitle color-danger">¥';
$out+=$escape(good.goods_price);
$out+='</div> <div class="item-text pull-right"> <span class="badge">x';
$out+=$escape(good.goods_num);
$out+='</span> </div> </div> </li> ';
});
$out+=' </ul> </div> </div> <div class="card-footer"> 实付款: ¥';
$out+=$escape(value.order_amount);
$out+=' <span class="pull-right" data-order_id="';
$out+=$escape(value.order_id);
$out+='" data-order_sn="';
$out+=$escape(value.order_sn);
$out+='"> ';
if(value.if_cancel){
$out+='<a href="#" class="button button-danger jOrderOp" data-optype="order_cancel">取消订单</a>';
}
$out+=' ';
if(value.if_cancel){
$out+='<a href="#pay" class="button button-fill button-danger jGoPay" data-pay_sn="';
$out+=$escape(value.pay_sn);
$out+='" data-order_total="';
$out+=$escape(value.order_amount);
$out+='">去支付</a>';
}
$out+=' ';
if(value.if_delete){
$out+='<a href="#" class="button button-danger jOrderOp" data-optype="order_del">删除订单</a>';
}
$out+=' ';
if(value.if_deliver){
$out+='<a href="#" class="button button-danger jSearchDeliver">查看物流</a>';
}
$out+=' ';
if(value.if_receive){
$out+='<a href="#" class="button button-danger jOrderOp" data-optype="order_receive">确认收货</a>';
}
$out+=' <!--';
if(value.if_evaluation){
$out+='<a href="" class="button button-danger jEvaluate">评论</a>';
}
$out+='--> </span> </div> </div> ';
});
$out+=' ';
if(hasmore){
$out+=' <div class="infinite-scroll-preloader"> <div class="preloader"></div> </div> ';
}
$out+=' ';
}
return new String($out);
});/*v:1*/
template('my_comments',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,goodsevallist=$data.goodsevallist,$each=$utils.$each,value=$data.value,i=$data.i,$escape=$utils.$escape,$out='';if(!goodsevallist){
$out+=' <div class="empty"> <p class="icon iconfont icon-form f14"></p> <p class="emt-txt">暂无商品评论</p> </div> ';
}else{
$out+=' ';
$each(goodsevallist,function(value,i){
$out+=' <li> <a href="" data-good_id="';
$out+=$escape(value.geval_goodsid);
$out+='" class="item-content jGo"> <div class="item-media"> <img class="b-lazy" data-src="';
$out+=$escape(value.goods_image_url);
$out+='" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="></div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title f12">';
$out+=$escape(value.geval_goodsname);
$out+='</div> </div> <div class="item-subtitle starts-';
$out+=$escape(value.geval_scores);
$out+='"> <span class="icon iconfont icon-start lv-1"></span> <span class="icon iconfont icon-start lv-2"></span> <span class="icon iconfont icon-start lv-3"></span> <span class="icon iconfont icon-start lv-4"></span> <span class="icon iconfont icon-start lv-5"></span> </div> <div class="item-text"> <span class="present f13 mr10">';
$out+=$escape(value.geval_content);
$out+='</span> </div> </div> </a> </li> ';
});
$out+=' ';
}
return new String($out);
});/*v:1*/
template('order_detail',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$escape=$utils.$escape,order_sn=$data.order_sn,add_time=$data.add_time,state_desc=$data.state_desc,order_id=$data.order_id,if_buyer_cancel=$data.if_buyer_cancel,pay_sn=$data.pay_sn,order_amount=$data.order_amount,if_delete=$data.if_delete,if_receive=$data.if_receive,if_evaluation=$data.if_evaluation,extend_order_common=$data.extend_order_common,chain_info=$data.chain_info,if_deliver=$data.if_deliver,chain_code=$data.chain_code,$each=$utils.$each,extend_order_goods=$data.extend_order_goods,value=$data.value,i=$data.i,goods_amount=$data.goods_amount,shipping_fee=$data.shipping_fee,$out='';$out+='<div class="card"> <div class="card-header"> 订单号:';
$out+=$escape(order_sn);
$out+=' <span class="pull-right">';
$out+=$escape(add_time);
$out+='</span></div> <div class="card-content"> <div class="card-content-inner"> ';
$out+=$escape(state_desc);
$out+=' <span class="pull-right" data-order_id="';
$out+=$escape(order_id);
$out+='" data-order_sn="';
$out+=$escape(order_sn);
$out+='"> ';
if(if_buyer_cancel){
$out+='<a href="#" class="button button-danger jOrderOp" data-optype="order_cancel">取消订单</a>';
}
$out+=' ';
if(if_buyer_cancel){
$out+='<a href="#pay" class="button button-fill button-danger jGoPay" data-pay_sn="';
$out+=$escape(pay_sn);
$out+='" data-order_total="';
$out+=$escape(order_amount);
$out+='">去支付</a>';
}
$out+=' ';
if(if_delete){
$out+='<a href="" class="button button-danger jOrderOp" data-optype="order_del">删除订单</a>';
}
$out+=' ';
if(if_receive){
$out+='<a href="" class="button button-danger jOrderOp" data-optype="order_receive">确认收货</a>';
}
$out+=' ';
if(if_evaluation){
$out+='<a href="" class="button button-danger jEvaluate">评论</a>';
}
$out+=' </span> </div> </div> </div> <div class="list-block media-list"> <div class="item-content"> <div class="item-media"> ';
if(extend_order_common){
$out+='收货地址:';
}
$out+=' ';
if(chain_info){
$out+='门店信息:';
}
$out+=' </div> <div class="item-inner"> ';
if(extend_order_common.reciver_info){
$out+=' <div class="item-title">';
$out+=$escape(extend_order_common.reciver_name);
$out+=' ';
$out+=$escape(extend_order_common.reciver_info.mob_phone);
$out+='</div> <div class="item-subtitle">';
$out+=$escape(extend_order_common.reciver_info.address);
$out+='</div> ';
}
$out+=' ';
if(chain_info){
$out+=' <div class="item-title">';
$out+=$escape(chain_info.chain_name);
$out+=' </div> <div class="item-subtitle"> <p>';
$out+=$escape(chain_info.area_info);
$out+=' ';
$out+=$escape(chain_info.chain_address);
$out+='</p> <p>营业时间:';
$out+=$escape(chain_info.chain_opening_hours);
$out+='</p> <p>电话:';
$out+=$escape(chain_info.chain_phone);
$out+='</p> </div> ';
}
$out+=' </div> </div> <div class="item-content"> <div class="item-media"> 配送方式: </div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title">';
if(chain_info){
$out+='自提';
}else{
$out+='普通快递';
}
$out+='</div> ';
if(if_deliver){
$out+=' <div class="item-after" data-order_sn="';
$out+=$escape(order_sn);
$out+='"> <a href="" class="button button-small jSearchDeliver">查看物流</a> </div> ';
}
$out+=' ';
if(chain_code != 0){
$out+=' <div class="item-after">提货码:';
$out+=$escape(chain_code);
$out+='</div> ';
}
$out+=' </div> </div> </div> </div> <div class="card"> <div class="card-content"> <div class="list-block media-list"> <ul> ';
$each(extend_order_goods,function(value,i){
$out+=' <li class="item-content"> <div class="item-media"> <img src="';
$out+=$escape(value.image_url);
$out+='" alt=""> </div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title">';
$out+=$escape(value.goods_name);
$out+='</div> </div> <div class="item-subtitle color-danger">';
$out+=$escape(value.goods_price);
$out+='</div> <div class="item-text pull-right"> <span class="badge">';
$out+=$escape(value.goods_num);
$out+='</span> </div> </div> </li> ';
});
$out+=' </ul> </div> </div> <div class="card-footer"> 数量:';
$out+=$escape(extend_order_goods.length);
$out+=' <span class="pull-right"> 合计:¥';
$out+=$escape(goods_amount);
$out+=' </span> </div> </div> <div class="list-block"> <ul> <li class="item-content"> <div class="item-inner"> <div class="item-title">商品金额</div> <div class="item-after">¥';
$out+=$escape(goods_amount);
$out+='</div> </div> </li> <li class="item-content"> <div class="item-inner"> <div class="item-title">配送费用</div> <div class="item-after">¥';
$out+=$escape(shipping_fee);
$out+='</div> </div> </li> <li class="item-content"> <div class="item-inner"> <div class="item-title">订单金额</div> <div class="item-after">¥';
$out+=$escape(order_amount);
$out+='</div> </div> </li> </ul> </div>';
return new String($out);
});/*v:1*/
template('order_evaluate',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,extend_order_goods=$data.extend_order_goods,value=$data.value,i=$data.i,$escape=$utils.$escape,order_id=$data.order_id,$out='';$out+=' <div class="list-block media-list"> <ul> ';
$each(extend_order_goods,function(value,i){
$out+=' <li class="item-content"> <div class="item-media"> <img src="';
$out+=$escape(value.image_url);
$out+='"> </div> <div class="item-inner" data-rec_id="';
$out+=$escape(value.rec_id);
$out+='"> <div class="item-title-row"> <div class="item-title starts-5" data-start="5"> <span class="icon iconfont icon-start lv-1" data-start="1"></span> <span class="icon iconfont icon-start lv-2" data-start="2"></span> <span class="icon iconfont icon-start lv-3" data-start="3"></span> <span class="icon iconfont icon-start lv-4" data-start="4"></span> <span class="icon iconfont icon-start lv-5" data-start="5"></span> </div> <div class="item-after"> 匿名:<span class="icon iconfont icon-roundcheck jAnony"></span> </div> </div> <div class="item-subtitle"> <textarea></textarea> </div> </div> </li> ';
});
$out+=' </ul> </div> <div class="content-block"> <a href="" class="button button-fill button-danger" id="evaluate_btn" data-order_id="';
$out+=$escape(order_id);
$out+='">提交评价</a> </div> ';
return new String($out);
});/*v:1*/
template('xianshi',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,xianshi=$data.xianshi,value=$data.value,i=$data.i,$escape=$utils.$escape,item=$data.item,k=$data.k,$out='';$each(xianshi,function(value,i){
$out+=' <div class="content-block-title">';
$out+=$escape(value.title);
$out+='<span class="pull-right countdown" data-endtime="';
$out+=$escape(value.endtime);
$out+='"></span></div> <div class="list-block media-list"> <ul class="list-container goods-list"> ';
$each(value.goods,function(item,k){
$out+=' <li> <a href="" data-good_id="';
$out+=$escape(item.goods_id);
$out+='" class="item-content jGo"> <div class="item-media"> <img class="b-lazy" data-src="';
$out+=$escape(item.goods_image_url);
$out+='" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="></div> <div class="item-inner"> <div class="item-title-row"> <div class="item-title f12">';
$out+=$escape(item.goods_name);
$out+='</div> </div> <!--<div class="item-subtitle">';
$out+=$escape(item.xianshi_discount);
$out+='</div>--> <div class="item-text"> <span class="present color-danger f13 mr10">¥';
$out+=$escape(item.xianshi_price);
$out+='</span> <span class="original f10 color-gray"><del>¥';
$out+=$escape(item.goods_price);
$out+='</del></span> </div> </div> </a> </li> ';
});
$out+=' </ul> </div> ';
});
return new String($out);
});

}()