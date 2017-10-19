<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/layout.min.css" rel="stylesheet" type="text/css">
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <!-- 最近浏览 -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $lang['goods_class_viewed_goods']; ?>
                </div>
                <div class="panel-body">
                    <ul class="aside-viewed">
                        <?php if (!empty($output['viewed_goods']) && is_array($output['viewed_goods']))
                        { ?>
                            <?php foreach ($output['viewed_goods'] as $k => $v)
                        { ?>
                            <li>
                                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id'])); ?>"
                                   target="_blank">
                                    <img src="<?php echo yf_thumb($v, 220); ?>" title="<?php echo $v['goods_name']; ?>"
                                         alt="<?php echo $v['goods_name']; ?>">
                                </a>
                                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $v['goods_id'])); ?>"
                                   target="_blank"><?php echo $v['goods_name']; ?></a>

                                <div
                                    class="g-price"><?php echo $lang['currency']; ?><?php echo ncPriceFormat($v['goods_promotion_price']); ?></div>
                            </li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_goodsbrowse&op=list"
                   class="aside-all-viewed">全部浏览历史</a>
            </div>
            <div class="aside-m"><?php echo loadadv(37, 'html'); ?></div>
        </div>
        <div class="col-md-10">
            <?php $dl = 1;  //dl标记?>
            <?php if ((!empty($output['brand_array']) && is_array($output['brand_array'])) || (!empty($output['attr_array']) && is_array($output['attr_array']))){ ?>
                <div class="choose">
                    <div class="choose-title">
                        <h1><?php if (!empty($output['show_keyword'])){  echo $output['show_keyword'];  } ?></h1>
                    </div>
                    <div class="choose-content">
                        <?php if ((isset($output['checked_brand']) && is_array($output['checked_brand'])) || (isset($output['checked_attr']) && is_array($output['checked_attr'])))
                        { ?>
                            <dl nc_type="ul_filter">
                                <dt><?php echo $lang['goods_class_index_selected'] . $lang['nc_colon']; ?></dt>
                                <dd>
                                    <?php if (isset($output['checked_brand']) && is_array($output['checked_brand']))
                                    { ?>
                                        <?php foreach ($output['checked_brand'] as $key => $val)
                                    { ?>
                                        <a href="<?php echo removeParam(array('b_id' => $key)); ?>">
                                        <span class="choosed">
                                            <?php echo $lang['goods_class_index_brand']; ?>:
                                            <em><?php echo $val['brand_name'] ?></em>
                                            <i class="fa fa-times"></i>
                                        </span>
                                        </a>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php if (isset($output['checked_attr']) && is_array($output['checked_attr']))
                                    { ?>
                                        <?php foreach ($output['checked_attr'] as $val)
                                    { ?>
                                        <span class="choosed" yf_id="choosed">
                                            <?php echo $val['attr_name'] . ':<em>' . $val['attr_value_name'] . '</em>' ?>
                                            <i class="fa fa-times"
                                               data-uri="<?php echo removeParam(array('a_id' => $val['attr_value_id'])); ?>">
                                            </i>
                                        </span>
                                    <?php } ?>
                                    <?php } ?>
                                </dd>
                            </dl>
                        <?php } ?>
                        <?php if (!isset($output['checked_brand']) || empty($output['checked_brand']))
                        { ?>
                            <?php if (!empty($output['brand_array']) && is_array($output['brand_array']))
                        { ?>
                            <dl>
                                <dt><?php echo $lang['goods_class_index_brand'] . $lang['nc_colon']; ?></dt>
                                <dd>
                                    <ul>
                                        <?php $i = 0;foreach ($output['brand_array'] as $k => $v){$i ++; ?>
                                            <li>
                                                <a href="<?php $b_id = (($_GET['b_id'] != '' && intval($_GET['b_id']) != 0) ? $_GET['b_id'] . '_' . $k : $k);
                                                echo replaceParam(array('b_id' => $b_id)); ?>">
                                                    <?php echo $v['brand_name']; ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </dd>
                            </dl>
                            <?php $dl ++;
                        } ?>
                        <?php } ?>
                        <?php if (!empty($output['attr_array']) && is_array($output['attr_array'])){ ?>
                            <?php $j = 0;
                            foreach ($output['attr_array'] as $key => $val){
                                $j ++; ?>
                                <?php if (!isset($output['checked_attr'][$key]) && !empty($val['value']) && is_array($val['value'])){ ?>
                                    <dl>
                                        <dt><?php echo $val['name'] . $lang['nc_colon']; ?></dt>
                                        <dd>
                                            <ul>
                                                <?php foreach ($val['value'] as $k => $v){?>
                                                    <li>
                                                        <a href="<?php $a_id = (($_GET['a_id'] != '' && $_GET['a_id'] != 0) ? $_GET['a_id'] . '_' . $k : $k);
                                                        echo replaceParam(array('a_id' => $a_id)); ?>"><?php echo $v['attr_value_name']; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </dd>
                                    </dl>
                                <?php } ?>
                                <?php $dl ++;
                            } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
<nav class="navbar navbar-default">
<ul class="nav navbar-nav">
    <li <?php if (!$_GET['key']){ ?>class="active"<?php } ?>>
        <a  href="<?php echo dropParam(array('order', 'key')); ?>" title="<?php echo $lang['goods_class_index_default_sort']; ?>"><?php echo $lang['goods_class_index_default']; ?></a>
    </li>
    <li <?php if ($_GET['key'] == '1'){ ?>class="active"<?php } ?>>
        <a  href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')) : replaceParam(array('key' => '1', 'order' => '2')); ?>">
        <?php echo $lang['goods_class_index_sold']; ?>
            <?php if ($_GET['key'] == '1'){ ?>
                <span class="glyphicon glyphicon-sort-by-attributes-alt"></span>
            <?php } ?>
    </a></li>
    <li <?php if ($_GET['key'] == '2'){ ?>class="active"<?php } ?>>
        <a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')) : replaceParam(array('key' => '2', 'order' => '2')); ?>">
            <?php echo $lang['goods_class_index_click'] ?>
            <?php if ($_GET['key'] == '2'){ ?>
                <span class="glyphicon <?php echo $_GET['order'] == 1 ? 'glyphicon-sort-by-attributes-alt' : 'glyphicon glyphicon-sort-by-attributes'; ?>" ></span>
            <?php } ?>
    </a></li>
    <li><a  <?php if ($_GET['key'] == '3'){ ?>class="active"<?php } ?> href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')) : replaceParam(array('key' => '3', 'order' => '2')); ?>"
       title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? $lang['goods_class_index_price_asc'] : $lang['goods_class_index_price_desc']; ?>">
            <?php echo $lang['goods_class_index_price']; ?>
            <?php if ($_GET['key'] == '3'){ ?>
                <span class="glyphicon <?php echo $_GET['order'] == 1 ? 'glyphicon-sort-by-attributes-alt' : 'glyphicon glyphicon-sort-by-attributes'; ?>" ></span>
            <?php } ?>
    </a></li>
</ul>
</nav>
                <div>
                    <?php require_once(TPL_MAIN . '/shop/home/goods.squares.php'); ?>
                </div>
                 <?php echo $output['show_page']; ?>
        </div>
    </div>
</div>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fly/jquery.fly.min.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL . '/pc/js/search_goods.js'; ?>"></script>
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fly/requestAnimationFrame.js"
        charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
    var defaultSmallGoodsImage = '<?php echo defaultGoodsImage(240);?>';
    var defaultTinyGoodsImage = '<?php echo defaultGoodsImage(60);?>';

    $(function () {
        //浮动导航  waypoints.js
        $('#filter_fix').waypoint(function (event, direction) {
            $(this).parent().toggleClass('sticky', direction === "down");
            event.stopPropagation();
        });
    });
</script>