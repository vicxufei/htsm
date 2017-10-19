<?php
/**
 * 菜单
 * @gyf
 */
defined('ByYfShop') or exit('非法进入,IP记录...');
$_menu['system'] = array (
        'name' => '平台',
        'child' => array (
                array(
                        'name' => $lang['nc_config'],
                        'child' => array(
                                'setting' => $lang['nc_web_set'],
                                'upload' => $lang['nc_upload_set'],
                                'message' => '邮件设置',
                                'taobao_api' => '淘宝接口',
                                'admin' => '权限设置',
                                'admin_log' => $lang['nc_admin_log'],
                                'area' => '地区设置',
                                'cache' => $lang['nc_admin_clear_cache'],
								
                        )
                ),
                array(
                        'name' => $lang['nc_member'],
                        'child' => array(
                                'member' => $lang['nc_member_manage'],
                                'account' => $lang['nc_web_account_syn']
                        )
                ),
                array(
                        'name' => $lang['nc_website'],
                        'child' => array(
                                'article_class' => $lang['nc_article_class'],
                                'article' => $lang['nc_article_manage'],
                                'document' => $lang['nc_document'],
                                'navigation' => $lang['nc_navigation'],
                                'adv' => $lang['nc_adv_manage'],
                                'rec_position' => $lang['nc_admin_res_position'],
                        )
                ),
				 array(
                        'name' => '运维应用',
                        'child' => array(
								'shopwwi' => '运维控件',
								'goods' => '评论添加'
                        )
                )
        ) 
);
