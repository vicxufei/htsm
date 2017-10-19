<?php
/**
 * 客户端商家令牌模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2012 ShopNC Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.com
 * @link       http://www.shopwwi.com
 * @since      File available since Release v1.1
 */

defined('ByShopWWI') or exit('Access Invalid!');

class mb_seller_tokenModel extends Model{
    public function __construct(){
        parent::__construct('mb_seller_token');
    }

    /**
     * 查询
     *
     * @param array $condition 查询条件
     * @return array
     */
    public function getSellerTokenInfo($condition) {
        return $this->where($condition)->find();
    }

    public function getSellerTokenInfoByToken($token) {
        if(empty($token)) {
            return null;
        }
        return $this->getSellerTokenInfo(array('token' => $token));
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addSellerToken($param){
        return $this->insert($param);
    }

    /**
     * 删除
     *
     * @param int $condition 条件
     * @return bool 布尔类型的返回结果
     */
    public function delSellerToken($condition){
        return $this->where($condition)->delete();
    }
}
