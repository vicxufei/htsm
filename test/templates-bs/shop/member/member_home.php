<?php defined('ByShopWWI') or exit('Access Invalid!');?>

<div id="member_center_box" class="mb-container">
</div>
<script type="text/javascript">
$(function(){
	var INFO_TYPE = ['order_info','sns_info','goods_info'];
	function _ajax_load(type) {
		$.ajax({
			   url: 'index.php?act=member&op=ajax_load_'+type,
			   success: function(html){
				   INFO_TYPE.shift();
				   if (INFO_TYPE[0]) {
					   _ajax_load(INFO_TYPE[0]);
				   }
				   $('#member_center_box').append(html);
			   }
		});
	}
	_ajax_load(INFO_TYPE[0])
});
</script>