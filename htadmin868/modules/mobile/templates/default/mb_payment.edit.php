<?php defined('ByShopWWI') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminMobile('mb_payment', 'payment_list');?>" title="返回手机支付方式列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>手机支付方式 - <?php echo $lang['nc_set'];?>“<?php echo $output['payment']['payment_name'];?>”</h3>
        <h5>手机客户端可使用支付方式/接口设置</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1" action="<?php echo urlAdminMobile('mb_payment', 'payment_save');?>">
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <input type="hidden" name="payment_code" value="<?php echo $output['payment']['payment_code'];?>" />
    <div class="ncap-form-default">
      <?php if ($output['payment']['payment_code'] == 'alipay') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>支付宝账号</label>
        </dt>
        <dd class="opt">
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['payment']['payment_config']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>交易安全校验码(key)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['payment']['payment_config']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作者身份(partner ID)</label>
        </dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['payment']['payment_config']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>APP唯一凭证(appid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appid" id="wxpay_appid" value="<?php echo $output['payment']['payment_config']['wxpay_appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>应用密钥(appsecret)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appsecret" id="wxpay_appsecret" value="<?php echo $output['payment']['payment_config']['wxpay_appsecret'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" style="display:none;">
        <dt class="tit">
          <label><em>*</em>应用校验码(appkey)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appkey" id="wxpay_appkey" value="<?php echo $output['payment']['payment_config']['wxpay_appkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">V3版本微信支付不需要填写此项</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户号(partnerid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_partnerid" id="wxpay_partnerid" value="<?php echo $output['payment']['payment_config']['wxpay_partnerid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户密钥(partnerkey)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_partnerkey" id="wxpay_partnerkey" value="<?php echo $output['payment']['payment_config']['wxpay_partnerkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <?php if ($output['payment']['payment_code'] == 'wxpay_jsapi') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>APP唯一凭证(appid)</label>
        </dt>
        <dd class="opt">
          <input name="appId" id="appId" value="<?php echo $output['payment']['payment_config']['appId'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>应用密钥(appsecret)</label>
        </dt>
        <dd class="opt">
          <input name="appSecret" id="appSecret" value="<?php echo $output['payment']['payment_config']['appSecret'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作者身份(partner ID)</label>
        </dt>
        <dd class="opt">
          <input name="partnerId" id="partnerId" value="<?php echo $output['payment']['payment_config']['partnerId'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
            <?php if ($output['payment']['payment_code'] == 'alipay_mb') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>支付宝账号</label>
        </dt>
        <dd class="opt">
          <input name="mb_alipay_account" id="mb_alipay_account" value="<?php echo $output['payment']['payment_config']['mb_alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>合作者身份(partner ID)</label>
        </dt>
        <dd class="opt">
          <input name="mb_alipay_partner" id="mb_alipay_partner" value="<?php echo $output['payment']['payment_config']['mb_alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit">启用</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="payment_state1" class="cb-enable <?php if($output['payment']['payment_state'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['nc_yes'];?></label>
            <label for="payment_state2" class="cb-disable <?php if($output['payment']['payment_state'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['nc_no'];?></label>
            <input type="radio" <?php if($output['payment']['payment_state'] == '1'){ ?>checked="checked"<?php }?> value="1" name="payment_state" id="payment_state1">
            <input type="radio" <?php if($output['payment']['payment_state'] == '0'){ ?>checked="checked"<?php }?> value="0" name="payment_state" id="payment_state2">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn_submit" ><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
		<?php if ($output['payment']['payment_code'] == 'alipay') { ?>
        rules : {
            alipay_account : {
                required   : true
            },
            alipay_key : {
                required   : true
            },
            alipay_partner : {
                required   : true
            }
        },
        messages : {
            alipay_account  : {
                required : '<i class="fa fa-exclamation-circle"></i>支付宝账号不能为空'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i>交易安全校验码不能为空'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
        rules : {
            wxpay_key : {
                required   : true
            },
            wxpay_partner : {
                required   : true
            }
        },
        messages : {
            wxpay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i>交易安全校验码不能为空'
            },
            wxpay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay_jsapi') { ?>
        rules : {
            appId : {
                required   : true
            },
            appSecret : {
                required   : true
            },
            partnerId : {
                required   : true
            }
        },
        messages : {
            appId  : {
                required : '<i class="fa fa-exclamation-circle"></i>APP唯一凭证不能为空'
            },
            appSecret  : {
                required : '<i class="fa fa-exclamation-circle"></i>应用密钥不能为空'
            },
            partnerId  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
				<?php if ($output['payment']['payment_code'] == 'alipay_mb') { ?>
        rules : {
            mb_alipay_account : {
                required   : true
            },
            mb_alipay_partner : {
                required   : true
            }
        },
        messages : {
            mb_alipay_account  : {
                required : '<i class="fa fa-exclamation-circle"></i>支付宝账号不能为空'
            },
            mb_alipay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i>合作者身份不能为空'
            }
        }
		<?php } ?>
    });

    $('#btn_submit').on('click', function() {
        $('#post_form').submit();
    });
});
</script>
