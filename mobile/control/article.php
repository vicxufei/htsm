<?php
/**
 * 文章
 *
 *
 *
 * @copyright  Copyright (c) 2007-2015 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */



defined('ByShopWWI') or exit('Access Invalid!');
class articleControl extends mobileHomeControl{

    public function __construct() {
        parent::__construct();
    }

    public function indexOp() {
        $this->article_listOp();
    }

    protected function article_listOp() {
        $article_type = $_GET['type'];

        if (C('cache_open')) {
            if ($article = rkcache("index/article")) {
                if($article_type == 'gong_gao'){
                    output_data($article['show_article']);
                }else{
                    output_data($article['article_list']);
                }
            }
        }
    }

    public function showOp(){
        if(empty($_GET['id'])){
            output_error('缺少参数');//'缺少参数:文章编号'
        }
        /**
         * 根据文章编号获取文章信息
         */
        $article_model  = Model('article');
        $article    = $article_model->getOneArticle(intval($_GET['id']));
        if(empty($article) || !is_array($article) || $article['article_show']=='0'){
            output_error('该文章并不存在');//'该文章并不存在'
        }
        output_data($article);

    }

}
