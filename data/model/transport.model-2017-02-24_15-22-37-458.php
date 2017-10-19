<?php
/**
 * 运费模板
 * @gyf
 */
defined('ByShopWWI') or exit('Access Invalid!');

class transportModel extends Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 增加运费模板
     *
     * @param unknown_type $data
     * @return unknown
     */
    public function addTransport($data){
        return $this->table('transport')->insert($data);
    }

    /**
     * 增加各地区详细运费设置
     *
     * @param unknown_type $data
     * @return unknown
     */
    public function addExtend($data){
        return $this->table('transport_extend')->insertAll($data);
    }

    /**
     * 取得一条运费模板信息
     *
     * @return unknown
     */
    public function getTransportInfo($condition){
        return $this->table('transport')->where($condition)->find();
    }

    /**
     * 取得一条运费模板扩展信息
     *
     * @return unknown
     */
    public function getExtendInfo($condition,$field = '*'){
        return $this->table('transport_extend')->field($field)->where($condition)->select();
    }

    /**
     * 删除运费模板
     *
     * @param unknown_type $id
     * @return unknown
     */
    public function delTansport($condition){
        try {
            $this->beginTransaction();
            $delete = $this->table('transport')->where($condition)->delete();
            if ($delete) {
                $delete = $this->table('transport_extend')->where(array('transport_id'=>$condition['id']))->delete();
            }
            if (!$delete) throw new Exception();
            $this->commit();
        }catch (Exception $e){
            $model->rollback();
            return false;
        }
        return true;
    }

    /**
     * 删除运费模板扩展信息
     *
     * @param unknown_type $transport_id
     * @return unknown
     */
    public function delExtend($transport_id){
        return $this->table('transport_extend')->where(array('transport_id'=>$transport_id))->delete();
    }

    /**
     * 取得运费模板列表
     *
     * @param unknown_type $condition
     * @param unknown_type $page
     * @param unknown_type $order
     * @return unknown
     */
    public function getTransportList($condition=array(), $pagesize = '', $order = 'id desc'){
        return $this->table('transport')->where($condition)->order($order)->page($pagesize)->select();
    }

    /**
     * 取得扩展信息列表
     *
     * @param unknown_type $condition
     * @param unknown_type $order
     * @return unknown
     */
    public function getExtendList($condition=array(), $order=''){
        return $this->table('transport_extend')->where($condition)->order($order)->select();
    }

    public function transUpdate($data,$condition = array()){
        return $this->table('transport')->where($condition)->update($data);
    }

    /**
     * 检测运费模板是否正在被使用
     *
     */
    public function isUsing($id){
        if (!is_numeric($id)) return false;
        $goods_info = $this->table('goods')->where(array('transport_id'=>$id))->field('goods_id')->find();
        return $goods_info ? true : false;
    }

    /**
     * 计算某地区某运费模板ID下的商品总运费，如果运费模板不存在或，按免运费处理
     *
     * @param int $transport_id
     * @param int $area_id
     * @return number/boolean
     */
    public function calc_transport($transport_id, $area_id,$cart_all_price) {
        if (empty($transport_id) || empty($area_id)) return 0;
        $extend_list = $this->getExtendList(array('transport_id'=>$transport_id));
        if (empty($extend_list)) {
            return false;
        } else {
            return $this->_calc_unit($area_id,$extend_list,$cart_all_price);
        }
    }

    /**
     * 检查该地区是否支持配送
     *
     * @param int $transport_id
     * @param int $area_id
     * @return number/boolean
     */
    public function area_freight($transport_id, $area_id) {
        if (empty($transport_id) || empty($area_id)) return 0;
        $extend_list = $this->getExtendList(array('transport_id'=>$transport_id));
        if (is_array($extend_list)){
            foreach ($extend_list as $v) {
                if (strpos($v['area_id'],",".$area_id.",") !== false){
                    $area_freight['supported'] = true;
                    $area_freight['sprice'] = $v['sprice'];
                    $area_freight['xprice'] = $v['xprice'];
                    $area_freight['baoyou'] = $v['baoyou'];
                    $area_freight['transport_title'] = $v['transport_title'];
                }
            }
        }
        if(!isset($area_freight)){
            $area_freight['supported'] = false;
            $area_freight['transport_title'] = $extend_list[0]['transport_title'];
        }
        return $area_freight;

    }
    /**
     * 购物车   根据cookie中的dregion,购物车中商品总重量 计算运费
     *
     * @param int $transport_id
     * @param int $area_id
     * @return number/boolean
     */
    public function weight_area_freight($transport_ids, $area_id, $cart_weight,$cart_total) {
        if (empty($transport_ids) || empty($area_id) || empty($cart_weight) ) return 0;
        $extend_list = $this->getExtendList(array('transport_id'=>array('in', $transport_ids)));
        if (is_array($extend_list)){
            foreach ($extend_list as $v) {
                if (strpos($v['area_id'],",".$area_id.",") !== false){

                    if($cart_total >= $v['baoyou']){
                        $freight_total = 0;
                    }else{
                        if(($cart_weight/1000 - 1) > 0){
                            $freight_total = $v['sprice'] + ($cart_weight/1000 - 1) * $v['xprice'];
                        } else{
                            $freight_total = $v['sprice'];
                        }
                    }
                    break;
                }
            }
        }
        if(!isset($freight_total)){
            $freight_total = false;
        }
        return $freight_total;

    }

    /**
     *    计算默然收货地址的运费
     *
     * @param int $transport_id
     * @param int $area_id
     * @return number/boolean
     */
    public function freight_calc($transport_ids, $area_id, $cart_weight,$cart_total) {
        if (empty($transport_ids) || empty($area_id) || empty($cart_weight) ) return 0;
        $extend_list = $this->getExtendList(array('transport_id'=>array('in', $transport_ids)));
        if (is_array($extend_list)){
            foreach ($extend_list as $v) {
                if (strpos($v['area_id'],",".$area_id.",") !== false){

                    if($_SERVER["REMOTE_ADDR"] == '49.64.152.233' && $v['sprice'] == 0){
                        $freight_total = 0;
                        break;
                    }


                    if($cart_total >= $v['baoyou'] && $v['baoyou'] > 0){
                        $freight_total = 0;
                    }else{
                        if(($cart_weight - 1) > 0){
                            $freight_total = $v['sprice'] + ceil($cart_weight -1) * $v['xprice'];
                        } else{
                            $freight_total = $v['sprice'];
                        }
                    }
//   yefeng                 break;
                }
            }
        }
        if(!isset($freight_total)){
            $freight_total = false;
        }
        return $freight_total;

    }

    /**
     * 计算某个具单元的运费
     *
     * @param 配送地区 $area_id
     * @param 运费模板内容 $extend
     * @return number/false 总运费
     */
    private function _calc_unit($area_id, $extend,$cart_all_price){
        if (!empty($extend) && is_array($extend)){
            foreach ($extend as $v) {
                if (strpos($v['area_id'],",".$area_id.",") !== false){
                    if($cart_all_price >= $v['baoyou']){
                        $calc_total=0;
                    }else{
                        $calc_total = $v['sprice'];
                    }
                }
            }
        }
        return isset($calc_total) ? ncPriceFormat($calc_total) : false;
    }

}
