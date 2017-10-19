<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/wwi-main.css" rel="stylesheet" type="text/css">
<style
    type="text/css">.public-nav-layout, .classtab a.curr, .head-search-bar .search-form, .public-nav-layout .category .hover .class {
        background: #FF9F00;
    }

    .public-head-layout .logo-test {
        color: #FF9F00
    }

    .public-nav-layout.category .sub-class {
        border-color: #FF9F00;
    }

    .nc-appbar-tabs a.compare {
        display: none !important;
    }</style>
<div class="goodsclass">
    <div class="classtab"><a href="javascript:void(0);" class="curr all">商品分类</a><a
            href="<?php echo urlshop('brand'); ?>">全部品牌</a></div>
    <div class="classlst"><?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class']))
        { ?><?php foreach ($output['show_goods_class'] as $key => $gc_list)
        { ?>
            <div class="classtit"><span><a
                    href="<?php echo urlShop('search', 'index', array('cate_id' => $gc_list['gc_id'])); ?>"><?php echo $gc_list['gc_name']; ?></a></span>
            </div>
            <div class="classcon"><?php if (!empty($gc_list['class2']))
                { ?><?php foreach ($gc_list['class2'] as $gc_list2)
                { ?>
                    <dl>
                    <dt>
                        <a href="<?php echo urlShop('search', 'index', array('cate_id' => $gc_list2['gc_id'])); ?>"><?php echo $gc_list2['gc_name']; ?></a>
                    </dt>
                    <dd><?php if (!empty($gc_list2['class3']))
                        { ?><?php foreach ($gc_list2['class3'] as $key => $gc_list3)
                        { ?><a
                            href="<?php echo urlShop('search', 'index', array('cate_id' => $gc_list3['gc_id'])); ?>"><?php echo $gc_list3['gc_name']; ?></a><?php } ?><?php } ?>
                    </dd></dl><?php } ?><?php } ?></div>        <?php } ?><?php } ?></div>
</div></div>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.masonry.js"></script>
<script>$(function () {
        $("#categoryList").masonry({itemSelector: '.classes'});
    });</script>