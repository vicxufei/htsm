<?php $i = 1; $areas = $output['areas']; foreach ($areas['region'] as $region => $provinceIds) { ?>
<li<?php if ($i % 2 == 0) echo ' class="even"'; ?>>
  <dl class="ncsc-region">
    <dt class="ncsc-region-title">
      <span>
      <input type="checkbox" id="J_Group_<?php echo $i; ?>" class="J_Group" value=""/>
      <label for="J_Group_<?php echo $i; ?>"><?php echo $region; ?></label>
      </span>
    </dt>
    <dd class="ncsc-province-list">
<!--省循环开始   -->
<?php foreach ($provinceIds as $provinceId) { ?>
      <div class="ncsc-province"><span class="ncsc-province-tab">
        <input type="checkbox" class="J_Province" id="J_Province_<?php echo $provinceId; ?>" value="<?php echo $provinceId; ?>"/>
        <label for="J_Province_<?php echo $provinceId; ?>"><?php echo $areas['name'][$provinceId]; ?></label>
        <span class="check_num"/> </span><i class="icon-angle-down trigger"></i>
        <div class="ncsc-citys-sub">
<?php foreach ($areas['children'][$provinceId] as $cityId) { ?>
          <br/>
          <span class="areas">
          <label for="J_City_<?php echo $cityId; ?>"><?php echo $areas['name'][$cityId]; ?></label>
          </span>
    <!--gyf县级市循环开始   -->
    <?php foreach ($areas['children'][$cityId] as $myId) { ?>
        <span class="areas">
          <input type="checkbox" class="J_City" id="J_City_<?php echo $myId; ?>" value="<?php echo $myId; ?>"/>
          <label for="J_City_<?php echo $myId; ?>"><?php echo $areas['name'][$myId]; ?></label>
          </span>
    <?php } ?>
    <!--gyf县级市循环结束   -->

<?php } ?>
          <p class="tr hr8"><a href="javascript:void(0);" class="ncbtn-mini ncbtn-bittersweet close_button">关闭</a></p>
        </div>
        </span>
      </div>
<?php } ?>
<!--省循环结束   -->
    </dd>
  </dl>
</li>
<?php $i++; } ?>
