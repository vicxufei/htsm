<?php defined('ByShopWWI') or exit('Access Invalid!'); ?>
<link href="<?php echo SHOP_TEMPLATES_URL; ?>/css/wwi-main.css" rel="stylesheet" type="text/css">
<style
    type="text/css">.public-nav-layout, .classtab a.curr, .head-search-bar .search-form, .public-nav-layout .category .hover .class {
        background: #46c9bb;
    }

    .public-head-layout .logo-test {
        color: #46c9bb
    }

    .public-nav-layout .category .sub-class {
        border-color: #46c9bb;
    }

    .nc-appbar-tabs a.compare {
        display: none !important;
    }</style>
<div class="goodsclass">
    <div class="classtab"><a href="<?php echo urlshop('category'); ?>">商品分类</a><a class="curr all"
                                                                                  href="javascript:void(0);">全部品牌</a>
    </div>
    <div class="brandlog"><?php if (!empty($output['brand_r']))
        { ?><?php foreach ($output['brand_r'] as $key => $brand_r)
        { ?>
            <dl>
            <dt><a href="<?php echo urlShop('brand', 'list', array('brand' => $brand_r['brand_id'])); ?>"><img
                        src="<?php echo brandImage($brand_r['brand_pic']); ?>"
                        alt="<?php echo $brand_r['brand_name']; ?>" title="<?php echo $brand_r['brand_name']; ?>"/></a>
            </dt>
            <dd>
                <a href="<?php echo urlShop('brand', 'list', array('brand' => $brand_r['brand_id'])); ?>"><?php echo $brand_r['brand_name']; ?></a>
            </dd> </dl> <?php } ?><?php } ?></div>
    <div class="brandtxt"> <?php if (!empty($output['brand_c']))
        { ?><?php foreach ($output['brand_c'] as $key => $brand_c)
        { ?>
            <dl>
            <dt><?php echo $key; ?> </dt>
            <ul><?php foreach ($brand_c['image'] as $key => $brand)
                {
                    ; ?>
                    <li><a href="<?php echo urlShop('brand', 'list', array('brand' => $brand['brand_id'])); ?>"
                           tit="<?php echo $brand['brand_initial']; ?>"
                           src="<?php echo brandImage($brand['brand_pic']); ?>"
                           title="<?php echo str_cut($brand['brand_introduction'], 150); ?>"><?php echo $brand['brand_name']; ?></a>
                    </li><?php } ?>
                <div class="clear"></div>
            </ul></dl><?php }
        } ?> </div>
</div>