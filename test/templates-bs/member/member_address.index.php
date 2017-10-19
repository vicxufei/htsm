<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<script src="<?php echo STATIC_URL;?>/js/dialog/dialog.js" type="text/javascript"></script>
<script src="<?php echo STATIC_URL;?>/js/jquery.validate.min.js" type="text/javascript"></script>

<div class="row">
  <div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>操作提示：</strong>最多可保存20个有效地址
  </div>
  <table class="table">
    <thead>
      <tr>
        <th ><?php echo $lang['member_address_receiver_name'];?></th>
        <th ><?php echo $lang['member_address_location'];?></th>
        <th >街道地址</th>
        <th ><?php echo $lang['member_address_phone'];?>/<?php echo $lang['member_address_mobile'];?></th>
        <th ></th>
        <th ><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <?php if(!empty($output['address_list']) && is_array($output['address_list'])){?>
    <tbody>
      <?php foreach($output['address_list'] as $key=>$address){?>
      <?php $address['address'] = $address['type'].$address['address']?>
      <tr>
        <td><?php echo $address['true_name'];?></td>
        <td><?php echo $address['area_info'];?></td>
        <td ><?php echo $address['address'];?></td>
        <td><p><?php echo $address['tel_phone'];?></p>
          <p><?php echo $address['mob_phone']; ?></p></td>
        <td><?php if ($address['is_default'] == '1') {?><b class="glyphicon glyphicon-ok" ></b>默认地址<?php } ?></td>
        <td>
          <div class="btn-group" role="group">
            <?php if (intval($address['dlyp_id'])) { ?>
              <a role="button" class="btn btn-default" data-toggle="modal" data-target="#my_address_edit"
                 href="javascript:void(0);"
                 dialog_id="daisou" dialog_width="900" dialog_title="<?php echo $lang['member_address_edit_address'];?>"
                 nc_type="dialog" uri="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_address&op=delivery_add&id=<?php echo $address['address_id'];?>"
              ><?php echo $lang['nc_edit'];?></p>
              </a>
            <?php } else { ?>
              <a role="button" class="btn btn-default" data-toggle="modal" data-target="#my_address_edit"
                 href="<?php echo MEMBER_SITE_URL;?>/index.php?act=member_address&op=address&type=edit&id=<?php echo $address['address_id'];?>"    dialog_title="<?php echo $lang['member_address_edit_address'];?>"
              ><?php echo $lang['nc_edit'];?>
              </a>
            <?php } ?>
            <a role="button" class="btn btn-default" href="javascript:void(0)"  onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', '<?php echo MEMBER_SITE_URL;?>/index.php?act=member_address&op=address&id=<?php echo $address['address_id'];?>');">
              <?php echo $lang['nc_del'];?>
            </a>
          </div>
        </td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr>
        <td><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
<div class="col-md-6 col-md-offset-3">
  <a role="button" class="btn btn-default" data-toggle="modal" data-target="#my_address_edit"
     href="index.php?act=member_address&op=address&type=add"  dialog_title="<?php echo $lang['member_address_new_address'];?>"
     title="<?php echo $lang['member_address_new_address'];?>"
  ><?php echo $lang['member_address_new_address'];?>
  </a>
  <?php if (C('delivery_isuse')) { ?>
    <a role="button" class="btn btn-default" data-toggle="modal" data-target="#ziti_edit" href="index.php?act=member_address&op=delivery_add" title="使用自提服务站">使用自提服务站</a>
    <div class="modal fade" id="ziti_edit" tabindex="-1" role="dialog" aria-labelledby="自提服务站" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        </div> <!-- /.modal-content -->
      </div> <!-- /.modal-dialog -->
    </div> <!-- /.modal -->
  <?php } ?>
</div>
  <div class="modal fade" id="my_address_edit" tabindex="-1" role="dialog" aria-labelledby="添加/修改收货地址" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
      </div> <!-- /.modal-content -->
    </div> <!-- /.modal-dialog -->
  </div> <!-- /.modal -->
</div>
<?php if (C('delivery_isuse')) { ?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<?php } ?>
