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
     * 删除地址
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function delAddress($condition){
        return $this->where($condition)->delete();
    }
}
