<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">收货地址</h4>
</div>
<form role="form" class="form-horizontal" method="post" action="index.php?act=member_address&op=address" id="address_form" target="_parent">
<div class="modal-body">
  <input type="hidden" name="form_submit" value="ok" />
  <input type="hidden" name="id" value="<?php echo $output['address_info']['address_id'];?>" />
  <input type="hidden" value="<?php echo $output['address_info']['city_id'];?>" name="city_id" id="_area_2">
  <input type="hidden" value="<?php echo $output['address_info']['area_id'];?>" name="area_id" id="_area">
<div class="form-group">
    <label class="col-sm-3 control-label" for="true_name"><?php echo $lang['member_address_receiver_name'].$lang['nc_colon'];?></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="true_name" placeholder="<?php echo $lang['member_address_input_name'];?>" value="<?php echo $output['address_info']['true_name'];?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3  control-label"><?php echo $lang['member_address_location'].$lang['nc_colon'];?></label>
    <div class="col-sm-9">
        <input type="hidden" id="region" class="form-control" name="region"  value="<?php echo $output['address_info']['area_info'];?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="address"><?php echo $lang['member_address_address'].$lang['nc_colon'];?></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="address" placeholder="<?php echo $lang['member_address_not_repeat'];?>" value="<?php echo $output['address_info']['address'];?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="tel_phone"><?php echo $lang['member_address_phone_num'].$lang['nc_colon'];?></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="tel_phone" placeholder="<?php echo $lang['member_address_area_num'];?> - <?php echo $lang['member_address_phone_num'];?> - <?php echo $lang['member_address_sub_phone'];?>" value="<?php echo $output['address_info']['tel_phone'];?>">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label" for="mob_phone"><?php echo $lang['member_address_mobile_num'].$lang['nc_colon'];?></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" name="mob_phone"  value="<?php echo $output['address_info']['mob_phone'];?>">
    </div>
</div>

  <?php if (!intval($output['address_info']['dlyp_id'])) { ?>
      <div class="checkbox">
          <label class="col-sm-8 col-sm-offset-2">
              <input type="checkbox" <?php if ($output['address_info']['is_default']) echo 'checked';?> name="is_default" id="is_default" value="1">
              设为默认地址<?php echo $lang['nc_colon'];?>
          </label>
      </div>
  <?php } ?>
  <?php if (C('delivery_isuse')) { ?>
    <a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet" id="zt"><i class="icon-flag"></i>使用自提服务站</a>
    <p class="text-center">当您需要对自己的收货地址保密或担心收货时间冲突时可使用该业务，
        <br/>添加后可在购物车中作为收货地址进行选择，货品将直接发送至自提服务站，
        <br/>到货后短信、站内消息进行通知，届时您可使用“自提码”至该服务站兑码取货。
    </p>

  <?php } ?>
  <div class="modal-footer">
      <button type="submit" class="btn btn-default" value="Submit">
          <?php if($output['type'] == 'add'){?><?php echo $lang['member_address_new_address'];?><?php }else{?><?php echo $lang['member_address_edit_address'];?><?php }?>
      </button>
      <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
</div>
</form>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
$(document).ready(function(){
	$("#region").nc_region();

    // 手机号码验证
    jQuery.validator.addMethod("isPhone", function(value, element) {
        var length = value.length;
        return this.optional(element) || (length == 11 && /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/.test(value));
    }, "请正确填写您的手机号码。");

	$('#address_form').validate({
    	submitHandler:function(form){
    		ajaxpost('address_form', '', '', 'onerror');
    	},
        errorElement : 'span',
        errorClass : 'help-block text-center',
        errorPlacement : function(error, element) {
            element.next().remove();
            element.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            element.closest('.form-group').append(error);
        },
        highlight : function(element) {
            $(element).closest('.form-group').addClass('has-error has-feedback');
        },
        success : function(label) {
            var el=label.closest('.form-group').find("input");
            el.next().remove();
            el.after('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
            label.closest('.form-group').removeClass('has-error').addClass("has-feedback has-success");
            label.remove();
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
            tel_phone : {
                required : check_phone,
                minlength : 6,
				maxlength : 20
            },
            mob_phone : {
                required : check_phone,
                isPhone : true
            }
        },
        messages : {
            true_name : {
                required : '<?php echo $lang['member_address_input_receiver'];?>'
            },
            address : {
                required : '<?php echo $lang['member_address_input_address'];?>'
            },
            tel_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_phone_rule'];?>',
				maxlength: '<?php echo $lang['member_address_phone_rule'];?>'
            },
            mob_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                isPhone : '<?php echo $lang['member_address_wrong_mobile'];?>'
            }
        },
        groups : {
            phone:'tel_phone mob_phone'
        }
    });
    $('#zt').on('click',function(){
    	DialogManager.close('my_address_edit');
    	ajax_form('daisou','使用代收货（自提）', '<?php echo MEMBER_SITE_URL;?>/index.php?act=member_address&op=delivery_add', '900',0);
    });
});
function check_phone(){
    return ($('input[name="tel_phone"]').val() == '' && $('input[name="mob_phone"]').val() == '');
}


</script>