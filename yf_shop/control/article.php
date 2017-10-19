<?php
/**
 * 文章
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');

class articleControl extends BaseHomeControl {
    /**
     * 单篇文章显示页面
     */
    public function showOp(){
        if(empty($_GET['article_id'])){
            showMessage('缺少参数','','html','error');//'缺少参数:文章编号'
        }
        /**
         * 根据文章编号获取文章信息
         */
        $article_model  = Model('article');
        $article    = $article_model->getOneArticle(intval($_GET['article_id']));
        if(empty($article) || !is_array($article) || $article['article_show']=='0'){
            showMessage('该文章并不存在','','html','error');//'该文章并不存在'
        }
        Tpl::output('article',$article);


        $seo_param = array();
        $seo_param['name'] = $article['article_title'];
        Model('seo')->type('article_content')->param($seo_param)->show();
        //Tpl::showpage('article_show');
          Tpl::display2('article');
    }
}
