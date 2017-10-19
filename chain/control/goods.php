<?php
/**
 * 物流自提服务站首页
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */
defined('ByShopWWI') or exit('Access Invalid!');

class goodsControl extends BaseChainCenterControl{
    public function __construct(){
        parent::__construct();
    }
    
    public function indexOp() {
        if($_SESSION['chain_user'] != 'ht_tc') {
          die;
        }
        $model_goods = Model('goods');
        $where = array();
        $where['store_id'] = $_SESSION['chain_store_id'];
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $where['goods_serial'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $where['goods_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        
        $goods_list = $model_goods->getGeneralGoodsCommonList($where, '*', 10);
        $stock_list = array();
        if (!empty($goods_list)) {
            $commonid_array = array();
            foreach ($goods_list as $val) {
                $commonid_array[] = $val['goods_commonid'];
            }
            $goodsid_array = $model_goods->getGoodsOnlineList(array('goods_commonid' => array('in', $commonid_array)), 'min(goods_id) goods_id,goods_commonid', 0, 'goods_id desc', 0, 'goods_commonid');
            $goodsid_array = array_under_reset($goodsid_array, 'goods_commonid');
            Tpl::output('goodsid_array', $goodsid_array);
            $stock_array = Model('chain_stock')->getChainStockList(array('chain_id' => $_SESSION['chain_id'], 'goods_commonid' => array('in', $commonid_array)));
            if (!empty($stock_array)) {
                foreach ($stock_array as $val) {
                    if (!isset($stock_list[$val['goods_commonid']])) {
                        $stock_list[$val['goods_commonid']]['stock'] = 0;
                    }
                    $stock_list[$val['goods_commonid']]['stock'] += intval($val['stock']);
                    $stock_list[$val['goods_commonid']]['goods_id'] = $val['goods_id'];
                }
            }
        }
        Tpl::output('stock_list', $stock_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::output('goods_list', $goods_list);
        
        $this->profile_menu('goods_list', 'goods_list');
        Tpl::showpage('goods.list');
    }

    public function index_newOp() {
      if($_SESSION['chain_user'] != 'ht_tc') {
        die;
      }
      $model_goods = Model('goods');
      $model_chain_stock = Model('chain_stock');
      $stock_array = $model_chain_stock->getChainStockList(array('chain_id' => $_SESSION['chain_id']), '*', 3000, 'stock asc');
      $commonid_array = array();
      foreach ($stock_array as $val) {
        $commonid_array[] = $val['goods_commonid'];
      }
      $goods_list = $model_goods->getGoodsOnlineList(array('goods_commonid' => array('in', $commonid_array)));

      $goods_list_array = array();
      foreach ($stock_array as $val) {
        foreach ($goods_list as $good_item) {
          if($val['goods_id'] == $good_item['goods_id']) {
            $good_item['stock'] = $val['stock'];
            $goods_list_array[] = $good_item;
          }
        }
      }

//      $goodsid_array = array_under_reset($goodsid_array, 'goods_commonid');
//      var_export($goods_list_array);
      Tpl::output('goods_list', $goods_list_array);
      Tpl::showpage('goods.list3');
    }

    public function index_newXXXOp() {
        if($_SESSION['chain_user'] != 'ht_tc') {
          die;
        }
        $model_goods = Model('goods');
        $where = array();
        $where['chain_id'] = $_SESSION['chain_store_id'];
        $where['goods_state'] = 1;
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['goods.goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $where['goods.goods_serial'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $where['goods.goods_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        $goods_list = $model_goods->getGoodsList2($where,'*','','chain_stock.stock',0,10);
//        var_dump($goods_list);
//        exit();
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::output('goods_list', $goods_list);

        $this->profile_menu('goods_list', 'goods_list');
        Tpl::showpage('goods.list2');
    }
    
    /**
     * 设置库存
     */
    public function set_stockOp() {
        $model_chain_stock = Model('chain_stock');
        if (chksubmit()) {
            foreach ($_POST['stock'] as $key => $val) {
                $insert = array();
                $insert['chain_id']         = $_SESSION['chain_id'];
                $insert['goods_id']         = intval($key);
                $insert['goods_commonid']   = intval($_POST['goods_commonid']);
                $insert['stock']            = intval($val);
                $model_chain_stock->addChainStock($insert);
            }
            showDialog('操作成功', 'reload', 'succ');
        }
        
        $common_id = intval($_GET['common_id']);
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
        if ($goodscommon_info['store_id'] != $_SESSION['chain_store_id']) {
            Tpl::output('error', true);
        }
        Tpl::output('goodscommon_info', $goodscommon_info);
        $spec_name = array_values((array)unserialize($goodscommon_info['spec_name']));
        Tpl::output('spec_name', $spec_name);
        
        $goods_info = $model_goods->getGeneralGoodsOnlineList(array('goods_commonid' => $common_id), 'goods_id,goods_spec,goods_serial,goods_price');
        
        $stock_info = $model_chain_stock->getChainStockList(array('chain_id' => $_SESSION['chain_id'], 'goods_commonid' => $common_id));
        $stock_info = array_under_reset($stock_info, 'goods_id');
        Tpl::output('stock_info', $stock_info);

        $goods_array = array();
        if (!empty($goods_info)) {
            foreach ($goods_info as $val) {
                $goods_spec = array_values((array)unserialize($val['goods_spec']));
                $goods_array[$val['goods_id']]['goods_spec'] = $goods_spec;
                $goods_array[$val['goods_id']]['goods_serial'] = $val['goods_serial'];
                $goods_array[$val['goods_id']]['goods_price'] = $val['goods_price'];
            }
        }

        Tpl::output('goods_array', $goods_array);
        Tpl::showpage('goods.set_stock', 'null_layout');
    }

    /**
     * 同步门店库存
     */
    public function sync_stockOp() {
        $url = 'http://192.168.0.151:48004/order/in?chain_id='.$_SESSION['chain_id'];
//        echo $url;
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址1
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转1
        curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容1
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回1

        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo '操作失败,请重试!';
        } else {
            echo '操作成功';
            echo $tmpInfo;
        }
        curl_close($curl); // 关键CURL会话
    }

  /**
   * 调拨库存
   */
  public function update_stockOp() {
    $url = 'http://192.168.0.151:48003/goods/in?chain_id='.$_SESSION['chain_id'];
//    echo $url;
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址1
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转1
    curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容1
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回1

    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
      echo '操作失败,请重试!';
    } else {
      echo '操作成功';
      echo $tmpInfo;
    }
    curl_close($curl); // 关键CURL会话
  }
    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key) {
        $menu_array = array();
        switch ($menu_type) {
            case 'goods_list':
                $menu_array = array(
                array('menu_key' => 'goods_list',    'menu_name' => '商品列表', 'menu_url' => urlChain('goods', 'index'))
                );
                break;
        }
        Tpl::output ( 'chain_menu', $menu_array );
        Tpl::output ( 'menu_key', $menu_key );
    }

}
