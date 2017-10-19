<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<?php if(!empty($output['nav_link_list']) && is_array($output['nav_link_list'])){?>
  <ol class="container breadcrumb">
    <?php foreach($output['nav_link_list'] as $nav_link){?>
    <?php if(!empty($nav_link['link'])){?>
    <li><a href="<?php echo $nav_link['link'];?>"><?php echo $nav_link['title'];?></a></li>
    <?php }else{?>
    <li class="active"><?php echo $nav_link['title'];?></li>
    <?php }?>
    <?php }?>
  </ol>
  <?php }?>

