<?php
/**
 * chat
 *
 * @网店运维提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopwwi.com
 * @link       交流群号：111731672
 */
defined('ByShopWWI') or exit('Access Invalid!');

class Chat {
	public static function getChatHtml($layout){
		$web_html = '';
		if ($layout != 'layout/msg_layout.php' && $layout != 'layout/store_joinin_layout.php'){
			$config_file = BASE_ROOT_PATH.DS.'chat'.DS.'config'.DS."config.ini.php";
			require_once $config_file;
			$avatar = getMemberAvatar($_SESSION['avatar']);
			$s_avatar = getStoreLogo($_SESSION['store_avatar']);
			$nchash = getNchash();
			$formhash = Security::getTokenValue();
			$css_url = CHAT_TEMPLATES_URL;
			$login_url = LOGIN_SITE_URL;
			$chat_url = CHAT_SITE_URL;
			$node_url = NODE_SITE_URL;
			$shop_url = SHOP_SITE_URL;

			$web_html = <<<EOT
					<link href="{$css_url}/css/chat.css" rel="stylesheet" type="text/css">
					<div style="clear: both;"></div>
					<div id="web_chat_dialog" style="display: none;float:right;">
					</div>
					<script type="text/javascript">
					var LOGIN_SITE_URL = '{$login_url}';
					var CHAT_SITE_URL = '{$chat_url}';
					var SHOP_SITE_URL = '{$shop_url}';
					var connect_url = "{$node_url}";

					var layout = "{$layout}";
					var act_op = "{$_GET['act']}_{$_GET['op']}";
					var chat_goods_id = "{$_GET['goods_id']}";
					var user = {};

					user['u_id'] = "{$_SESSION['member_id']}";
					user['u_name'] = "{$_SESSION['member_name']}";
					user['s_id'] = "{$_SESSION['store_id']}";
					user['s_name'] = "{$_SESSION['store_name']}";
					user['s_avatar'] = "{$s_avatar}";
					user['avatar'] = "{$avatar}";

					$("#chat_login").nc_login({
					  action:'/index.php?act=login',
					  nchash:'{$nchash}',
					  formhash:'{$formhash}'
					});
					</script>
EOT;
			if (defined('APP_ID') && APP_ID != 'shop'){
                $web_html .= '<link href="' . RESOURCE_SITE_URL . '/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">';
				$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/perfect-scrollbar.min.js"></script>';
				$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/jquery.mousewheel.js"></script>';
			}
			$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/jquery.charCount.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/jquery.smilies.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="'.CHAT_RESOURCE_URL.'/js/user.js" charset="utf-8"></script>';

		}
		if ($layout == 'layout/seller_layout.php'){
		    $web_html .= '<script type="text/javascript" src="'.CHAT_RESOURCE_URL.'/js/store.js" charset="utf-8"></script>';
		    $seller_smt_limits = '';
		    if (!empty($_SESSION['seller_smt_limits']) && is_array($_SESSION['seller_smt_limits'])) {
		        $seller_smt_limits = implode(',', $_SESSION['seller_smt_limits']);
		    }
			$web_html .= <<<EOT
					<script type="text/javascript">
					user['seller_id'] = "{$_SESSION['seller_id']}";
					user['seller_name'] = "{$_SESSION['seller_name']}";
					user['seller_is_admin'] = "{$_SESSION['seller_is_admin']}";
					var smt_limits = "{$seller_smt_limits}";
					</script>
EOT;
		}

		return $web_html;
	}
}
?>
