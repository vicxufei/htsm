<?php defined('ByYfShop') or exit('非法进入,IP记录...'); ?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="g-topbar">
    <div class="container">
        <nav class="navbar navbar-default">
            <p class="navbar-text">
                客服电话：0512-53228886 09:00-21:30(周一至周日)
            </p>
            <a id="gyfbutton" data-toggle="modal" data-target="#myModal">Click me</a>
            <script>
                $(function(){
                    $("#gyfbutton").click(function(){
                        $('#login_modal').modal('show');
                    });
                })
            </script>
            <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">登录</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" method="post" action="http://i.htths.com/index.php?XDEBUG_SESSION_START=PHPSTORM&act=login">
                                <input type="hidden" value="ok" name="form_submit">
                                <input type="hidden" value="IPNuEsBsCs6u1Jhrv3AnLhJufFxDs5r" name="formhash">
                                <input type="hidden" value="" name="nchash">
                                <input type="hidden" name="ref_url" value="http://mall.htths.com">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">用户名</label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="inputEmail3" name="user_name" placeholder="请输入用户名/手机号/邮箱">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="请输入密码">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"> Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" class="btn btn-default">登录</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($_SESSION['is_login'] == '1'){ ?>
                    <?php echo $lang['nc_hello']; ?>
                    <li>
                        <a rel="nofollow" href="<?php echo urlMember('member', 'home'); ?>"><?php echo $_SESSION['member_name']; ?></a>
                    </li>
                    <li>
                        <a rel="nofollow" href="<?php echo urlLogin('login', 'logout'); ?>"><?php echo $lang['nc_logout']; ?></a>
                    </li>
                <?php }else{ ?>
                    <li>
                        <a rel="nofollow" href="<?php echo urlMember('login'); ?>"><?php echo $lang['nc_login']; ?></a>
                    </li>
                    <li>
                        <a rel="nofollow" href="<?php echo urlLogin('login', 'register'); ?>"><?php echo $lang['nc_register']; ?></a>
                    </li>
                <?php } ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" rel="nofollow" href="<?php echo SHOP_SITE_URL; ?>/index.php?act=member_order">我的订单<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a rel="nofollow" href="<?php echo SHOP_SITE_URL; ?>/index.php?controller=member_order&state_type=state_send">待确认收货</a></li>
                        <li><a rel="nofollow" href="<?php echo SHOP_SITE_URL; ?>/index.php?controller=member_order&state_type=state_noeval">待评价交易</a></li>
                        <li><a rel="nofollow" href="<?php echo SHOP_SITE_URL; ?>/index.php?controller=member_favorite_goods&op=fglist">商品收藏</a></li>
                        <li><a rel="nofollow" href="<?php echo SHOP_SITE_URL; ?>/index.php?controller=member_favorite_store&op=fslist">店铺收藏</a></li>
                    </ul>
                </li>
                <?php if (C('mobile_isuse') && C('mobile_app')){ ?>
                    <li class="dropdown">
                        <a rel="nofollow" class="dropdown-toggle" data-toggle="dropdown" href="<?php echo BASE_SITE_URL; ?>/wap">移动端<b class="caret"></b></a>
                        <div class="dropdown-menu">
                            <img src="<?php echo UPLOAD_SITE_URL . DS . ATTACH_COMMON . DS . C('mobile_app'); ?>">
                            <?php if (C('mobile_apk'))
                            { ?>
                                <a rel="nofollow" href="<?php echo C('mobile_apk'); ?>" target="_blank"><i class="icon-android"></i>Android</a>
                            <?php } ?>
                            <?php if (C('mobile_ios'))
                            { ?>
                                <a rel="nofollow" href="<?php echo C('mobile_ios'); ?>" target="_blank"><i
                                        class="icon-apple"></i>iPhone</a>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">客户服务<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 2)); ?>">帮助中心</a></li>
                        <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 5)); ?>">售后服务</a></li>
                        <li><a href="<?php echo urlMember('article', 'article', array('ac_id' => 6)); ?>">客服中心</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>




