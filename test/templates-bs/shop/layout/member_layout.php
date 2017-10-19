<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<!doctype html>
<html lang="zh-CN">
<?php include template1('html5_meta'); ?>
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/home_header.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL; ?>/pc/css/member.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/member.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/ToolTip.js"></script>
<body>
<!-- 顶部s -->
<?php require_once template1('topbar'); ?>
<!-- 顶部e -->
<!-- 头部s -->
<?php require_once template1('site-head'); ?>
<!-- 头部e -->
<div class="wrapper">
    <div class="mb-left">
        <ul>
        <?php if (!empty($output['menu_list'])){ ?>
            <?php foreach ($output['menu_list'] as $key => $value){ ?>
            <li>
                <h3><?php echo $value['name']; ?></h3>
                <?php if (!empty($value['child'])){ ?>
                    <ul>
                        <?php foreach ($value['child'] as $key => $val){ ?>
                        <li <?php if ($key == $output['act']){ ?>class="selected"<?php } ?>>
                            <a href="<?php echo $val['url']; ?>"><?php echo $val['name']; ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </li>
        <?php } ?>
        <?php } ?>
        </ul>
    </div>
    <div class="mb-right">
        <div class="mb-info">
            <div class="avatar">
                <a href="<?php echo urlMember('member_information', 'avatar'); ?>" title="修改头像">
                    <img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']); ?>">
                    <div class="frame"></div>
                </a>
                <?php if (intval($output['message_num']) > 0){ ?>
                    <a href="<?php echo MEMBER_SITE_URL ?>/index.php?act=member_message&op=message" class="new-message" title="新消息"><?php echo intval($output['message_num']); ?></a>
                <?php } ?>
            </div>
            <dl>
                <dt>
                    <a href="<?php echo urlMember('member_information', 'member'); ?>" title="修改资料"><?php echo $output['member_info']['member_name']; ?></a>
                </dt>
                <dd>会员等级：
                    <?php if ($output['member_info']['level_name']){ ?>
                        <div class="mb-grade-mini" style="cursor:pointer;"
                             onclick="javascript:go('<?php echo urlShop('pointgrade', 'index'); ?>');"><?php echo $output['member_info']['level_name']; ?>
                            会员
                        </div>
                    <?php } ?>
                </dd>
                <dd>账户安全：
                    <div class="SAM">
                        <a href="<?php echo urlMember('member_security', 'index'); ?>" title="安全设置">
                            <?php if ($output['member_info']['security_level'] <= 1)
                            { ?>
                                <div id="low" class="SAM-info"><span><em></em></span><strong>低</strong></div>
                            <?php }
                            elseif ($output['member_info']['security_level'] == 2)
                            { ?>
                                <div id="normal" class="SAM-info"><span><em></em></span><strong>中</strong></div>
                            <?php }
                            else
                            { ?>
                                <div id="high" class="SAM-info"><span><em></em></span><strong>高</strong></div>
                            <?php } ?>
                        </a>
                    </div>
                </dd>
                <dd>用户财产：
                    <div class="user-account">
                        <ul>
                            <li id="pre-deposit">
                                <a href="<?php echo urlMember('predeposit', 'pd_log_list'); ?>" title="我的余额：￥<?php echo $output['member_info']['available_predeposit']; ?>"><span class="icon"></span> </a>
                            </li>
                            <li id="points">
                                <a href="<?php echo urlMember('member_points', 'index'); ?>" title="我的积分：<?php echo $output['member_info']['member_points']; ?>分"><span class="icon"></span></a>
                            </li>
                            <li id="voucher">
                                <a href="<?php echo urlMember('member_voucher', 'index'); ?>" title="我的代金券：<?php echo $output['member_info']['voucher_count']; ?>张"><span class="icon"></span</a>
                            </li>
                            <li id="envelope">
                                <a href="<?php echo urlMember('member_redpacket', 'index'); ?>" title="我的红包：<?php echo $output['member_info']['redpacket_count']; ?>张"><span class="icon"></span></a></li>
                        </ul>
                    </div>
                </dd>
            </dl>
        </div>
        <div class="notice">
            <ul class="line">
                <?php if (is_array($output['system_notice']) && !empty($output['system_notice']))
                { ?>
                    <?php foreach ($output['system_notice'] as $v)
                { ?>
                    <li><a <?php if ($v['article_url'] != '')
                           { ?>target="_blank"<?php } ?> href="<?php if ($v['article_url'] != '')
                        {
                            echo $v['article_url'];
                        }
                        else
                        {
                            echo urlMember('article', 'show', array('article_id' => $v['article_id']));
                        } ?>"><?php echo $v['article_title'] ?>
                            <time>(<?php echo date('Y-m-d', $v['article_time']); ?>)</time>
                        </a></li>
                <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <script>
            $(function () {
                var _wrap = $('ul.line');
                var _interval = 2000;
                var _moving;
                _wrap.hover(function () {
                        clearInterval(_moving);
                    },
                    function () {
                        _moving = setInterval(function () {
                                var _field = _wrap.find('li:first');
                                var _h = _field.height();
                                _field.animate({
                                        marginTop: -_h + 'px'
                                    },
                                    600,
                                    function () {
                                        _field.css('marginTop', 0).appendTo(_wrap);
                                    })
                            },
                            _interval)
                    }).trigger('mouseleave');
            });
        </script>
        <?php require_once($tpl_file); ?>
    </div>
    <div class="clear"></div>
</div>
<?php require_once template1('copyright'); ?>
</body>
</html>