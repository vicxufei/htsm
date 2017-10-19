<?php defined('ByShopWWI') or exit('Access Invalid!');?>

<div class="row">
  <div class="col-md-6">
      <?php if ($output['member_info']['security_level'] <= 1) { ?>
        <div class="progress">
          <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
            <span class="sr-only">30% Complete</span>
          </div>
        </div>
        <div class="current low">当前安全等级：<strong>低</strong><span>(建议您开启全部安全设置，以保障账户及资金安全)</span></div>
      <?php } else if ($output['member_info']['security_level'] == 2) { ?>
        <div class="progress">
          <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
            <span class="sr-only">60% Complete</span>
          </div>
        </div>
        <div class="current normal">当前安全等级：<strong>中</strong><span>(建议您开启全部安全设置，以保障账户及资金安全)</span></div>
      <?php } else { ?>
        <div class="progress">
          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
            <span class="sr-only">100% Complete</span>
          </div>
        </div>
        <div class="current high">当前安全等级：<strong>高</strong><span>(您目前账户运行很安全)</span></div>
      <?php } ?>
  </div>
  <div class="col-md-6">
      <p>上次登录：<?php echo date('Y年m月d日 H:i:s',$output['member_info']['member_old_login_time']);?>&#12288;|&#12288;IP地址:<?php echo $output['member_info']['member_old_login_ip'];?></p>
      <p>（不是您登录的？请立即<a href="index.php?controller=member_security&action=auth&type=modify_pwd">“更改密码”</a>）。</p>
  </div>

  <div class="col-md-12">
    <div class="alert alert-success" role="alert">
      <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
      <span class="sr-only">登录密码</span>
      登录密码:<strong>已设置</strong>
      <a role="button" class="btn btn-default" href="index.php?act=member_security&op=auth&type=modify_pwd">修改密码</a>
      <span class="pull-right">互联网账号存在被盗风险，建议您定期更改密码以保护账户安全。</span>
    </div>
    <div class="alert <?php echo $output['member_info']['member_email_bind'] == 1 ? 'alert-success' : 'alert-warning';?>" role="alert">
      <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
      <span class="sr-only">邮箱绑定</span>
      邮箱绑定:当前邮箱<em><?php echo encryptShow($output['member_info']['member_email'],4,4);?></em>,<strong><?php echo $output['member_info']['member_email_bind'] == 1 ? '已绑定' : '未绑定';?></strong>
      <a role="button" class="btn btn-default"href="index.php?act=member_security&op=auth&type=modify_email">绑定邮箱</a>
      <a role="button" class="btn btn-default"href="index.php?act=member_security&op=auth&type=modify_email">修改邮箱</a>
      <span class="pull-right">绑定后，在忘记密码时可通过邮件找回密码，还可接收资讯提醒服务!</span>
    </div>
    <div class="alert <?php echo $output['member_info']['member_mobile_bind'] == 1 ? 'alert-success' : 'alert-warning';?>" role="alert">
      <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
      <span class="sr-only">手机绑定</span>
      手机绑定:当前号码<?php echo encryptShow($output['member_info']['member_mobile'],4,4);?>,<strong><?php echo $output['member_info']['member_mobile_bind'] == 1 ? '已绑定' : '未绑定';?></strong>
      <a role="button" class="btn btn-default" href="index.php?act=member_security&op=auth&type=modify_mobile">绑定手机</a>
      <a role="button" class="btn btn-default" href="index.php?act=member_security&op=auth&type=modify_mobile">修改手机</a>
      <span class="pull-right">绑定后，可用于手机号快速登录，在忘记密码时也可通过短信验证码找回密码!</span>
    </div>
    <div class="alert <?php echo $output['member_info']['member_paypwd'] == 1 ? 'alert-success' : 'alert-warning';?>" role="alert">
      <span class="glyphicon glyphicon-flash" aria-hidden="true"></span>
      <span class="sr-only">支付密码</span>
      支付密码:<strong><?php echo $output['member_info']['member_paypwd'] != '' ? '已设置' : '未设置';?></strong>
      <a role="button" class="btn btn-default" href="index.php?act=member_security&op=auth&type=modify_paypwd">设置密码</a>
      <a role="button" class="btn btn-default" href="index.php?act=member_security&op=auth&type=modify_paypwd">修改密码</a>
      <span class="pull-right">设置支付密码后，在使用账户中余额时，需输入支付密码。</span>
    </div>
  </div>

</div>
