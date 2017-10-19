<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>支付成功</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
    body{
        margin: 0;
        padding: 0;
    }
    .main{
        flex: 1;
        align-items: center;
        justify-content: center;
        background-color: gray;
        width: 100vw;
        height: 100vh;
        padding: 0 auto;
    }
    .text{
        width: 200px;
        margin: 0 auto;
        padding-top: 50vh;
        text-align: center;
    }
    .success{
        color: #fff;
        margin-bottom: 30px;
    }
    .btn_card{
        height: 40px;
        text-align: center;
        background: #39a1e8;
        color: #fff;
        border-radius: 4px;
        padding: 6px 20px;
        text-decoration: none;
    }
</style>
<body>
<div class="main">
    <div class="text">
        <div class="success">支付成功!请返回</div>
        <a class="btn_card" id="close" href="javascript:api.closeWin();">关闭窗口</a>
    </div>
</div>
</body>
</html>