<?php
/**
 * 行业分析
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('ByShopWWI') or exit('Access Invalid!');

class statistics_flowControl extends BaseChainCenterControl{
    private $search_arr;//处理后的参数

    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        $this->search_arr = $model->dealwithSearchTime($this->search_arr);
        //获得系统年份
        $year_arr = getSystemYearArr();
        //获得系统月份
        $month_arr = getSystemMonthArr();
        //获得本月的周时间段
        $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
        Tpl::output('year_arr', $year_arr);
        Tpl::output('month_arr', $month_arr);
        Tpl::output('week_arr', $week_arr);
        Tpl::output('search_arr', $this->search_arr);
    }


    /**
     * 店铺流量统计
     */
    public function storeflowOp() {
        echo 123;
        exit();
        $store_id = intval($_SESSION['store_id']);
        //确定统计分表名称
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'week';
        }
        $model = Model('stat');
        //获得搜索的开始时间和结束时间
        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);
//        var_export($searchtime_arr);
//        exit();
        $where = array();
        $where['chain_id'] = 11;
        $where['order_state'] = 40;
        $where['add_time'] = array('between',$searchtime_arr);

        $field = ' SUM(order_amount) as amount';
        $statlist_tmp = $model->orderAmount($where, $field);
        var_export($statlist_tmp);
        if ($statlist_tmp){
            foreach((array)$statlist_tmp as $k=>$v){
                $statlist[$v['timeval']] = floatval($v['amount']);
            }
        }
        //得到统计图数据
        $stat_arr['legend']['enabled'] = false;
        $stat_arr['series'][0]['name'] = '访问量';
        $stat_arr['series'][0]['data'] = array_values($statlist);
        $stat_arr['title'] = '店铺访问量统计';
        $stat_arr['yAxis'] = '访问次数';
        $stat_json = getStatData_LineLabels($stat_arr);
        Tpl::output('stat_json',$stat_json);
        self::profile_menu('storeflow');
        Tpl::showpage('stat.flow.store');
    }

}
