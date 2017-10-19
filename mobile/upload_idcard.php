<html>
<body>

<form action="http://api.htths.com/index.php?XDEBUG_SESSION_START=PHPSTORM&act=member_address&op=idcard_pic&key=c9604a6b3bc12f2d476b7805179fb7d5"
    method="post"
    enctype="multipart/form-data">
<!--<label for="file">身份证号码:</label><input type="text" name="card_no"/><br/>-->
    <label for="file">name="address_id"收货地址ID:</label><input type="text" name="address_id"/><br/>
<!--    <label for="file">真实姓名:</label><input type="text" name="true_name"/><br/>-->
<label for="file">name="fpic"身份证正面:</label><input type="file" name="fpic"/><br/>
    <label for="file">name="bpic"身份证反面:</label><input type="file" name="bpic"/><br/>
<input type="submit" name="submit" value="Submit"/>
</form>


<form action="http://api.htths.com/index.php?XDEBUG_SESSION_START=PHPSTORM&act=member_index&op=avatarupload&key=c9604a6b3bc12f2d476b7805179fb7d5"
      method="post"
      enctype="multipart/form-data">
        <label for="file">头像上传:</label><input type="file" name="pic"/><br/>
<input type="submit" name="submit" value="Submit"/>
</form>
</body>
</html>