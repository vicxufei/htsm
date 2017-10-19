<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/4/3
 * Time: 上午10:23
 */
defined('ByShopWWI') or exit('Access Invalid!');
class address_idcardModel extends Model {

    public function __construct() {
        parent::__construct('address_idcard');
    }


    /**
     * 新增身份证信息
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addIdCard($param){
        return $this->insert($param);
    }
}
