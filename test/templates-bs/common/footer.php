<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div class="footer">
    <div class="ft-promise">
        <div class="wrapper">
            <dl>
                <dt class="zheng">正品保障</dt>
                <dd>
                    <p><strong><a href="http://sale.suning.com/images/advertise/yy/keepHeart/keepheart.html?relPro" target="_blank" rel="nofollow" name="public0_none_wb_fwxx01">正品保障</a></strong></p>
                    <p>正品保障，提供发票</p>
                </dd>
            </dl>
            <dl>
                <dt class="jisu">急速物流</dt>
                <dd>
                    <p><strong><a href="http://image.suning.cn/images/advertise/yy/keepHeart/keepheart.html?halfDay" target="_blank" rel="nofollow" name="public0_none_wb_fwxx02">急速物流</a></strong></p>
                    <p>急速物流，急速送达</p>
                </dd>
            </dl>
            <dl>
                <dt class="wuyou">无忧售后</dt>
                <dd>
                    <p><strong><a href="http://image.suning.cn/images/advertise/yy/keepHeart/keepheart.html?speed" target="_blank" rel="nofollow" name="public0_none_wb_fwxx03">无忧售后</a></strong></p>
                    <p>7天无理由退换货</p>
                </dd>
            </dl>
            <dl>
                <dt class="help">帮助中心</dt>
                <dd>
                    <p><strong><a href="http://help.suning.com/" target="_blank" rel="nofollow" name="public0_none_wb_fwxx05">帮助中心</a></strong></p>
                    <p>您的购物指南</p>
                </dd>
            </dl>
        </div>
    </div>
    <div class="ft-faq">
        <div class="wrapper">
            <?php if (is_array($output['article_list']) && !empty($output['article_list'])){ ?>
            <ul><?php foreach ($output['article_list'] as $k => $article_class){ ?>
                <?php if (!empty($article_class)){ ?>
                <li>
                    <dl class="s<?php echo '' . $k + 1; ?>">
                        <dt>
                            <?php if (is_array($article_class['class'])){echo $article_class['class']['ac_name'];} ?>
                        </dt>
                        <?php if (is_array($article_class['list']) && !empty($article_class['list'])){ ?>
                            <?php foreach ($article_class['list'] as $article){ ?>
                            <dd>
                                 <a href="<?php if ($article['article_url'] != ''){echo $article['article_url'];}else{echo urlMember('article', 'show', array('article_id' => $article['article_id']));} ?>"
                                    title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title']; ?>
                                 </a>
                            </dd>
                            <?php }} ?>
                    </dl>
                </li><?php }
                } ?>
            </ul><?php } ?>
            <ul class="ft-qrcode">
                <li>
                    <p>太划算App下载</p>
                    <img src="http://img2.htths.com/shop/common/mb_app.png" alt="太划算客户端二维码" />
                </li>
                <li>
                    <p>和团商贸微信公众号</p>
                    <img src="http://www.htths.com/js/suspension/htsm.jpg" alt="和团商贸微信公众号" />
                </li>
            </ul>
        </div>
    </div>
</div>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.cookie.js"></script>