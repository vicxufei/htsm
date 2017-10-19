<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<nav class="navbar yf-nav">
    <div class="container">
        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo SHOP_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="active"';} ?>><?php echo $lang['nc_index'];?></a>
            </li>
            <?php foreach ($output['show_goods_class'] as $key => $val){ ?>
            <li class="dropdown">
                <a <?php if($_GET['cate_id'] == $val['gc_id']) {echo 'class="active"';} ?> href=" <?php if (!empty($val['channel_id'])){echo urlShop('channel', 'index', array('id' => $val['channel_id']));}else{echo urlShop('search', 'index', array('cate_id' => $val['gc_id']));} ?>">
                    <?php echo $val['gc_name']; ?>
                </a>
                <div class="dropdown-menu" cat_menu_id="<?php echo $val['gc_id']; ?>">
                    <div class="pin-down-cate">
                        <?php if (!empty($val['class2']) && is_array($val['class2'])){ ?>
                            <?php foreach ($val['class2'] as $k => $v){ ?>
                                <dl class="item-list">
                                <dt>
                                    <a href="<?php echo urlShop('search', 'index', array('cate_id' => $v['gc_id'])); ?>"><?php echo $v['gc_name']; ?></a>
                                </dt>
                                <dd><?php if (!empty($v['class3']) && is_array($v['class3'])){ ?>
                                        <?php foreach ($v['class3'] as $k3 => $v3){ ?>
                                            <a href="<?php echo urlShop('search', 'index', array('cate_id' => $v3['gc_id'])); ?>">
                                            <?php echo $v3['gc_name']; ?></a>
                                        <?php }} ?>
                                </dd>
                                </dl>
                            <?php }} ?>
                    </div>
                    <div class="sub-class-right"><?php if (!empty($val['cn_brands']))
                        { ?>
                            <div class="brands-list">
                            <ul><?php foreach ($val['cn_brands'] as $brand)
                                { ?>
                                    <li> <a
                                        href="<?php echo urlShop('brand', 'list', array('brand' => $brand['brand_id'])); ?>"
                                        title="<?php echo $brand['brand_name']; ?>"><?php if ($brand['brand_pic'] != '')
                                        { ?><img src="<?php echo brandImage($brand['brand_pic']); ?>"/><?php } ?>
                                        <span><?php echo $brand['brand_name']; ?></span></a></li><?php } ?></ul>
                            </div><?php } ?>
                    <div class="adv-promotions"><?php if ($val['cn_adv1'] != '')
                        { ?>
                        <a <?php echo $val['cn_adv1_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="' . $val['cn_adv1_link'] . '"'; ?>>
                            <img src="<?php echo $val['cn_adv1']; ?>"></a><?php } ?><?php if ($val['cn_adv2'] != '')
                        { ?>
                        <a <?php echo $val['cn_adv2_link'] == '' ? 'href="javascript:;"' : 'target="_blank" href="' . $val['cn_adv2_link'] . '"'; ?>>
                            <img src="<?php echo $val['cn_adv2']; ?>"></a><?php } ?></div>
                    </div>
                </div>
            </li><?php } ?>
        </ul>
    </div>
</nav>
