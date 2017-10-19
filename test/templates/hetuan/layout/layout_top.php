<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="global-topbar">
    <div class="wrapper">
        <ul class="sn-block sn-login fl">
            <?php if ($_SESSION['is_login'] == '1')
            { ?>
                <li>
                    <?php echo $lang['nc_hello']; ?>
                    <a rel="nofollow"
                       href="<?php echo urlShop('member', 'home'); ?>"><?php echo $_SESSION['member_name']; ?></a>
                    <span class="split"></span>
                    <a rel="nofollow"
                       href="<?php echo urlLogin('login', 'logout'); ?>"><?php echo $lang['nc_logout']; ?></a>
                </li>
            <?php }
            else
            { ?>
                <li><?php echo $lang['nc_hello'] . $lang['nc_comma'] . $lang['welcome_to_site'] . $output['setting_config']['site_name']; ?>
                    !,请
                    <a rel="nofollow" href="<?php echo urlMember('login'); ?>"><?php echo $lang['nc_login']; ?></a>
                    <span class="split"></span>
                    <a rel="nofollow"
                       href="<?php echo urlLogin('login', 'register'); ?>"><?php echo $lang['nc_register']; ?></a>
                </li>
            <?php } ?>
        </ul>
        <ul class="sn-block sn-quick fr">
            <?php if (C('mobile_isuse') && C('mobile_app'))
            { ?>
                <li class="jMenu">
                    <a class="menu-hd block-item" href="<?php echo BASE_SITE_URL; ?>/wap">移动端<b class="arrow"></b></a>
                    <div class="menu-bd">
                        <img src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . C('mobile_app'); ?>">
                        <?php if (C('mobile_apk'))
                        { ?>
                            <a href="<?php echo C('mobile_apk'); ?>" target="_blank"><i class="icon-android"></i>Android</a>
                        <?php } ?>
                        <?php if (C('mobile_ios'))
                        { ?>
                            <a href="<?php echo C('mobile_ios'); ?>" target="_blank"><i
                                    class="icon-apple"></i>iPhone</a>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <li class="jMenu">
                <a class="menu-hd" href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_order">我的订单<b class="arrow"></b></a>
                <div class="menu-bd">
                    <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_order&state_type=state_new">待付款订单</a>
                    <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_order&state_type=state_send">待确认收货</a>
                    <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_order&state_type=state_noeval">待评价交易</a>
                    <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_favorite_goods&op=fglist">商品收藏</a>
                    <a href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_favorite_store&op=fslist">店铺收藏</a>
                </div>
            </li>
            <li class="jMenu">
                <a href="#">客户服务</a><i></i>

                <div class="menu-bd" style="display: none;">
                    <ul>
                        <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 2)); ?>">帮助中心</a></li>
                        <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 5)); ?>">售后服务</a></li>
                        <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 6)); ?>">客服中心</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
<?php if ($output['hidden_nctoolbar'] != 1)
{ ?>
    <div id="ncToolbar" class="nc-appbar">
        <div class="nc-appbar-tabs" id="appBarTabs">
            <div class="ever">
                <?php if (!$output['hidden_rtoolbar_cart'])
                { ?>
                    <div class="cart"><a href="javascript:void(0);" id="rtoolbar_cart"><span class="icon"></span> <span
                                class="name">购物车</span><i id="rtoobar_cart_count" class="new_msg"
                                                          style="display:none;"></i></a></div>
                <?php } ?>
                <?php if (!$output['hidden_rtoolbar_compare'])
                { ?>
                    <div class="compare"><a href="javascript:void(0);" id="compare"><span class="icon"></span><span
                                class="tit">商品对比</span></a></div>
                <?php } ?>
            </div>
            <div class="variation">
                <div class="middle">
                    <?php if ($_SESSION['is_login'])
                    { ?>
                        <div class="user" nctype="a-barUserInfo">
                            <a href="javascript:void(0);">
                                <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']); ?>"/>
                                </div>
                                <span class="tit">我的账户</span>
                            </a></div>
                        <div class="user-info" nctype="barUserInfo" style="display:none;"><i class="arrow"></i>

                            <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']); ?>"/>

                                <div class="frame"></div>
                            </div>
                            <dl>
                                <dt>Hi, <?php echo $_SESSION['member_name']; ?></dt>
                                <dd>当前等级：<strong
                                        nctype="barMemberGrade"><?php echo $output['member_info']['level_name']; ?></strong>
                                </dd>
                                <dd>当前经验值：<strong
                                        nctype="barMemberExp"><?php echo $output['member_info']['member_exppoints']; ?></strong>
                                </dd>
                            </dl>
                        </div>
                    <?php }
                    else
                    { ?>
                        <div class="user" nctype="a-barLoginBox">
                            <a href="javascript:void(0);">
                                <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']); ?>"/>
                                </div>
                                <span class="tit">会员登录</span>
                            </a>
                        </div>
                        <div class="user-login-box" nctype="barLoginBox" style="display:none;"><i class="arrow"></i> <a
                                href="javascript:void(0);" class="close-a" nctype="close-barLoginBox" title="关闭">X</a>

                            <form id="login_form" method="post" action="<?php echo urlLogin('login', 'login'); ?>"
                                  onsubmit="ajaxpost('login_form', '', '', 'onerror')">
                                <?php Security::getToken(); ?> <input type="hidden" name="form_submit" value="ok"/>
                                <input name="nchash" type="hidden" value="<?php echo getNchash('login', 'index'); ?>">
                                <dl>
                                    <dt><strong>登录名</strong></dt>
                                    <dd>
                                        <input type="text" class="text" autocomplete="off" name="user_name" autofocus>
                                        <label></label>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><strong>登录密码</strong><a
                                            href="<?php echo urlLogin('login', 'forget_password'); ?>" target="_blank">忘记登录密码？</a>
                                    </dt>
                                    <dd>
                                        <input type="password" class="text" name="password" autocomplete="off">
                                        <label></label>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><strong>验证码</strong><a href="javascript:void(0)" class="ml5"
                                                               onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&amp;op=makecode&amp;nchash=<?php echo getNchash('login', 'index'); ?>&amp;t=' + Math.random();">更换验证码</a>
                                    </dt>
                                    <dd>
                                        <input type="text" name="captcha" autocomplete="off" class="text w130"
                                               id="captcha" maxlength="4" size="10">
                                        <img src="" name="codeimage" border="0" id="codeimage" class="vt">
                                        <label></label>
                                    </dd>
                                </dl>
                                <div class="bottom">
                                    <input type="submit" class="submit" value="确认">
                                    <input type="hidden" value="" name="ref_url">
                                    <a href="<?php echo urlLogin('login', 'register', array('ref_url' => $_GET['ref_url'])); ?>"
                                       target="_blank">注册新用户</a>
                                    <?php if (C('qq_isuse') == 1 || C('sina_isuse') == 1 || C('weixin_isuse') == 1)
                                    { ?>
                                        <h4><?php echo $lang['nc_otherlogintip']; ?></h4>
                                        <?php if (C('weixin_isuse') == 1)
                                    { ?>
                                    <a href="javascript:void(0);"
                                       onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo urlLogin('connect_wx', 'index'); ?>', 360);"
                                       title="微信账号登录" class="mr20">微信</a><?php } ?>
                                        <?php if (C('sina_isuse') == 1)
                                    { ?>
                                    <a href="<?php echo MEMBER_SITE_URL; ?>/api.php?act=tosina" title="新浪微博账号登录"
                                       class="mr20">新浪微博</a><?php } ?>
                                        <?php if (C('qq_isuse') == 1)
                                    { ?><a href="<?php echo MEMBER_SITE_URL; ?>/api.php?act=toqq" title="QQ账号登录"
                                           class="mr20">QQ账号</a><?php } ?><?php } ?>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <div class="prech">&nbsp;</div>
                    <?php if (C('node_chat'))
                    { ?>
                        <div class="chat"><a href="javascript:void(0);" id="chat_show_user"><span class="icon"></span><i
                                    id="new_msg" class="new_msg" style="display:none;"></i><span class="tit">在线联系</span></a>
                        </div>
                    <?php } ?>
                </div>
                <div class="l_qrcode"><a href="javascript:void(0);" class=""><span class="icon"></span><code><img
                                src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_MOBILE . DS . C('mobile_wx'); ?>"></code></a>
                </div>
                <div class="gotop"><a href="javascript:void(0);" id="gotop"><span class="icon"></span><span class="tit">返回顶部</span></a>
                </div>
            </div>
            <div class="content-box" id="content-cart">
                <div class="top">
                    <h3>我的购物车</h3>
                    <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
                <div id="rtoolbar_cartlist"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        //登录开关状态
        var connect_qq = "<?php echo C('qq_isuse')?>";
        var connect_sn = "<?php echo C('sina_isuse')?>";
        var connect_wx = "<?php echo C('weixin_isuse')?>";
        $(function () {
            $(".l_qrcode a").hover(function () {
                    $(this).addClass("hover");
                },
                function () {
                    $(this).removeClass("hover");
                });

        });
        //返回顶部
        backTop = function (btnId) {
            var btn = document.getElementById(btnId);
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            window.onscroll = set;
            btn.onclick = function () {
                btn.style.opacity = "0.5";
                window.onscroll = null;
                this.timer = setInterval(function () {
                    scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                    scrollTop -= Math.ceil(scrollTop * 0.1);
                    if (scrollTop == 0) clearInterval(btn.timer, window.onscroll = set);
                    if (document.documentElement.scrollTop > 0) document.documentElement.scrollTop = scrollTop;
                    if (document.body.scrollTop > 0) document.body.scrollTop = scrollTop;
                }, 10);
            };
            function set() {
                scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                btn.style.opacity = scrollTop ? '1' : "0.5";
            }
        };
        backTop('gotop');

        //动画显示边条内容区域
        $(function () {
            ncToolbar();
            $(window).resize(function () {
                ncToolbar();
            });
            function ncToolbar() {
                if ($(window).width() >= 1240) {
                    $('#appBarTabs >.variation').show();
                } else {
                    $('#appBarTabs >.variation').hide();
                }
            }

            $('#appBarTabs').hover(
                function () {
                    $('#appBarTabs >.variation').show();
                },
                function () {
                    ncToolbar();
                }
            );
            $("#compare").click(function () {
                if ($("#content-compare").css('right') == '-210px') {
                    loadCompare(false);
                    $('#content-cart').animate({'right': '-210px'});
                    $("#content-compare").animate({right: '35px'});
                } else {
                    $(".close").click();
                    $(".chat-list").css("display", 'none');
                }
            });
            $("#rtoolbar_cart").click(function () {
                if ($("#content-cart").css('right') == '-210px') {
                    $('#content-compare').animate({'right': '-210px'});
                    $("#content-cart").animate({right: '35px'});
                    if (!$("#rtoolbar_cartlist").html()) {
                        $("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
                    }
                } else {
                    $(".close").click();
                    $(".chat-list").css("display", 'none');
                }
            });
            $(".close").click(function () {
                $(".content-box").animate({right: '-210px'});
            });

            $(".quick-menu dl").hover(function () {
                    $(this).addClass("hover");
                },
                function () {
                    $(this).removeClass("hover");
                });
            $(".links_a").hover(function () {
                    $(this).addClass("hover");
                },
                function () {
                    $(this).removeClass("hover");
                });

            // 右侧bar用户信息
            $('div[nctype="a-barUserInfo"]').click(function () {
                $('div[nctype="barUserInfo"]').toggle();
            });
            // 右侧bar登录
            $('div[nctype="a-barLoginBox"]').click(function () {
                $('div[nctype="barLoginBox"]').toggle();
                document.getElementById('codeimage').src = 'index.php?act=seccode&op=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();
            });
            $('a[nctype="close-barLoginBox"]').click(function () {
                $('div[nctype="barLoginBox"]').toggle();
            });
            <?php if ($output['cart_goods_num'] > 0) { ?>
            $('#rtoobar_cart_count').html(<?php echo $output['cart_goods_num'];?>).show();
            <?php } ?>
        });
    </script>
<?php } ?>
