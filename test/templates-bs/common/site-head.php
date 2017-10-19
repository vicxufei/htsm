<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div class="g-header">
    <div class="container">
        <div class="row">
            <div><a class="col-md-2 site-logo" href="<?php echo SHOP_SITE_URL; ?>">太划算商城</a></div>
            <div class="col-md-2 slogan"><?php echo $output['setting_config']['shopwwi_stitle']; ?></div>
            <div class="col-md-6 head-search-layout">
                <div class="head-search-bar" id="head-search-bar">
                    <form role="form" action="<?php echo SHOP_SITE_URL; ?>" method="get" id="top_search_form">
                        <?php if ($_GET['keyword']){
                            $keyword = stripslashes($_GET['keyword']);
                        }
                        elseif ($output['rec_search_list']){
                            $_stmp = $output['rec_search_list'][array_rand($output['rec_search_list'])];
                            $keyword_name = $_stmp['name'];
                            $keyword_value = $_stmp['value'];
                        }else{
                            $keyword = '';
                        } ?>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="keyword"
                                   name="keyword"
                                   value="<?php echo $keyword; ?>"
                                   placeholder="<?php echo $keyword_name ? $keyword_name : '请输入您要搜索的商品关键字'; ?>"
                                   data-value="<?php echo rawurlencode($keyword_value); ?>" autocomplete="off">
               <span class="input-group-btn">
                  <button class="btn btn-default" type="button"><?php echo $lang['nc_common_search']; ?></button>
               </span>
                        </div>
                    </form>
                    <div class="search-tip list-group" id="search-tip">
                    <?php if (is_array($output['his_search_list']) && !empty($output['his_search_list'])){ ?>
                        <?php foreach ($output['his_search_list'] as $v){ ?>
                            <a class="list-group-item" href="<?php echo urlShop('search', 'index', array('keyword' => $v)); ?>"><?php echo $v ?></a>
                    <?php } ?>
                    <?php } ?>
                        <a class="list-group-item" href="javascript:void(0);" id="search-his-del">清除</a>
                    </div>
                </div>
                <div class="kw-suggest">
                <?php if (is_array($output['hot_search']) && !empty($output['hot_search'])){
                    foreach ($output['hot_search'] as $val){ ?>
                        <a href="<?php echo urlShop('search', 'index', array('keyword' => $val)); ?>"><?php echo $val; ?></a>
                    <?php }
                } ?>
                </div>
            </div>
        </div>
    </div>
</div>