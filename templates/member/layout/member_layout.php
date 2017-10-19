<?php defined('ByShopWWI') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh-CN">
<?php include template1('html5_meta');?>
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/home_header.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/pc/css/member.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js"></script>
<body>
<!-- 顶部s -->
<?php require_once template1('topbar'); ?>
<!-- 顶部e -->
<!-- 头部s -->
<?php require_once template1('site-head'); ?>
<!-- 头部e -->
<div class="container">
    <div class="row">
        <div class="col-md-2">
        <?php if (!empty($output['menu_list'])) {?>
            <?php foreach ($output['menu_list'] as $key => $value) {?>
                <div class="list-group">
                    <li class="list-group-item list-group-item-success"><?php echo $value['name'];?></li>
                    <?php if (!empty($value['child'])) {?>
                        <?php foreach ($value['child'] as $k => $v) {?>
                            <a class="list-group-item <?php if ($k == $output['act']) {?>list-group-item-info<?php }?>"
                               href="<?php echo $v['url'];?>"><?php echo $v['name'];?>
                            </a>
                        <?php }?>
                    <?php }?>
                </div>
            <?php }?>
        <?php }?>
        </div>
        <div class="col-md-10">
            <div class="notice">
                <ul class="line">
                    <?php if (is_array($output['system_notice']) && !empty($output['system_notice'])) { ?>
                        <?php foreach ($output['system_notice'] as $v) { ?>
                            <li><a <?php if($v['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($v['article_url']!='')echo $v['article_url'];else echo urlMember('article', 'show', array('article_id'=>$v['article_id']));?>"><?php echo $v['article_title']?>
                                    <time>(<?php echo date('Y-m-d',$v['article_time']);?>)</time>
                                </a> </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <?php require_once($tpl_file);?>
        </div>
    </div>
</div>

<?php require_once template1('footer');?>
</body></html>