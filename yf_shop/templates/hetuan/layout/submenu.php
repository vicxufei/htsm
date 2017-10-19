<ul class="tab pngFix">
  <?php if(is_array($output['member_menu']) and !empty($output['member_menu'])) { 
			foreach ($output['member_menu'] as $key => $val) {
				if($val['menu_key'] == $output['menu_key']) {
					echo '<li class="active"><a '.(isset($val['target'])?"target=".$val['target']:"").' href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
				} else {
					echo '<li class="normal"><a '.(isset($val['target'])?"target=".$val['target']:"").' href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
				}
			}
        }
        ?>

  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=6">沙溪门店</a></li>
  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=7">浏河门店</a></li>
  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=8">浏家港门店</a></li>
  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=9">浮桥门店</a></li>
  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=13">陆渡门店</a></li>
  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=12">直塘门店</a></li>
  <li class="normal"><a href="http://www.htths.com/index.php?act=store_order&op=index&chain_id=14">牌楼门店</a></li>
</ul>

