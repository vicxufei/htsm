<?php
//require_once "jssdk.php";
//$jssdk = new JSSDK("wxeda52f5413d2e571", "300f96311731a9705afbcbc1a60759bc");
//$signPackage = $jssdk->GetSignPackage();
//?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
    // 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
    // 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
//    wx.config({
//        appId: '<?php //echo $signPackage["appId"];?>//',
//        timestamp: <?php //echo $signPackage["timestamp"];?>//,
//        nonceStr: '<?php //echo $signPackage["nonceStr"];?>//',
//        signature: '<?php //echo $signPackage["signature"];?>//',
//        jsApiList: [
//            // 所有要调用的 API 都要加到这个列表中
//        ]
//    });
    wx.config({
        appId: 'wxeda52f5413d2e571',
        timestamp: 1479359563,
        nonceStr: '07wMeMAGQZc6k2yM',
        signature: '326911667b3fe9466968e2cb615b974cf36f1a01',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            'onMenuShareTimeline', //分享到朋友圈
            'onMenuShareAppMessage', //分享给朋友
        ]
    });
    wx.ready(function () {
        var options = {
            title: '测试分享的标题', // 分享标题
            link: '', // 分享链接，记得使用绝对路径
            imgUrl: 'http://img2.htths.com/shop/store/goods/1/1_05204396762395153.jpg', // 分享图标，记得使用绝对路径
            desc: '这里是分享的描述', // 分享描述
            success: function () {
//                console.info('分享成功！');
                alert('分享成功了哦');
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                alert('取消分享');
//                console.info('取消分享！');
                // 用户取消分享后执行的回调函数
            }
        }
        wx.onMenuShareTimeline(options); // 分享到朋友圈
        wx.onMenuShareAppMessage(options); // 分享给朋友
        wx.onMenuShareQQ(options); // 分享到QQ
        // 在这里调用 API
    });
</script>
</html>