<?php

class Page {
    /**
     * url参数中页码参数名
     */
    private $page_name = "curpage";
    /**
     * 信息总数
     */
    private $total_num = 1;
    /**
     * 页码链接
     */
    private $page_url = "";
    /**
     * 每页信息数量
     */
    private $each_num = 10;
    /**
     * 当前页码
     */
    private $now_page = 1;
    /**
     * 设置页码总数
     */
    private $total_page = 1;
    /**
     * 输出样式
     * 4、5为商城伪静态专用样式
     */
    private $style = 2;
    /**
     * ajax 分页 预留，目前先不使用
     * 0为不使用，1为使用，默认为0
     */
    private $ajax = 0;
    /**
     * 首页
     */
    private $pre_home = "";
    /**
     * 末页
     */
    private $pre_last = "";
    /**
     * 上一页
     */
    private $pre_page = "";
    /**
     * 下一页
     */
    private $next_page = "";
    /**
     * 页码 样式左边界符
     */
    private $left_html = "";
    /**
     * 页码 样式右边界符
     */
    private $right_html = "";
    /**
     * 选中样式左边界符
     */
    private $left_current_html = "<li>";
    /**
     * 选中样式右边界符
     */
    private $right_current_html = "</li>";
    /**
     * 省略号样式左边界符
     */
    private $left_ellipsis_html = "";
    /**
     * 省略号样式右边界符
     */
    private $right_ellipsis_html = "";

    public function __construct() {
        Language::read("core_lang_index");
        $lang = Language::getLangContent();
        $this->pre_home = $lang["first_page"];
        $this->pre_last = $lang["last_page"];
        $this->pre_page = $lang["pre_page"];
        $this->next_page = $lang["next_page"];
        $this->setNowPage($_GET[$this->page_name] ? $_GET[$this->page_name] : $_POST[$this->page_name]);
        if (!in_array($this->style, array(
            4,
            5
        ))) {
            $this->setPageUrl();
        }
    }
    public function get($key) {
        return $this->$key;
    }
    public function set($key, $value) {
        return $this->$key = $value;
    }
    public function setPageName($page_name) {
        $this->page_name = $page_name;
        return true;
    }
    /**
     * 设置当前页码
     *
     * @param int $page 当前页数
     * @return bool 布尔类型的返回结果
     */
    public function setNowPage($page) {
        $this->now_page = intval($page) > 0 ? intval($page) : 1;
        return true;
    }
    /**
     * 设置每页数量
     *
     * @param int $num 每页显示的信息数
     * @return bool 布尔类型的返回结果
     */
    public function setEachNum($num) {
        $this->each_num = intval($num) > 0 ? intval($num) : 10;
        return true;
    }
    public function setStyle($style) {
        $this->style = ($style == 'admin' ? 2 : $style);
        return true;
    }
    public function setTotalNum($total_num) {
        $this->total_num = $total_num;
        return true;
    }
    public function getNowPage() {
        return $this->now_page;
    }
    public function getTotalPage() {
        if ($this->total_page == 1) {
            $this->setTotalPage();
        }
        return $this->total_page;
    }
    public function getTotalNum() {
        return $this->total_num;
    }
    public function getEachNum() {
        return $this->each_num;
    }
    public function getLimitStart() {
        if ($this->getNowPage() <= 1) {
            $tmp = 0;
        } else {
            $this->setTotalPage();
            $this->now_page = $this->now_page > $this->total_page ? $this->total_page : $this->now_page;
            $tmp = ($this->getNowPage() - 1) * $this->getEachNum();
        }
        return $tmp;
    }
    public function getLimitEnd() {
        $tmp = $this->getNowPage() * $this->getEachNum();
        if ($tmp > $this->getTotalNum()) {
            $tmp = $this->getTotalNum();
        }
        return $tmp;
    }
    public function setTotalPage() {
        $this->total_page = ceil($this->getTotalNum() / $this->getEachNum());
    }
    public function show($style = null) {
        /**
         * 设置总数
         */
        $this->setTotalPage();
        if (!is_null($style)) {
            $this->style = $style;
        }
        $html_page = '';
        $this->left_current_html = '<b>';
        $this->right_current_html = '</b>';
        switch ($this->style) {
            case '1':
                $html_page.= '<ul>';
                if ($this->getNowPage() <= 1) {
                    $html_page.= '<li>' . $this->pre_page . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->pre_page . '</a></li>';
                }
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page.= '<li>' . $this->next_page . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->next_page . '</a></li>';
                }
                $html_page.= '</ul>';
                break;

            case '2':
                $html_page.= '<ul>';
                if ($this->getNowPage() <= 1) {
                    $html_page.= '<li><a href="#">' . $this->pre_home . '</a></li>';
                    $html_page.= '<li>' . $this->pre_page . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->page_url . '1">' . $this->pre_home . '</a></li>';
                    $html_page.= '<li><a  href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->pre_page . '</a></li>';
                }
                $html_page.= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page.= '<li>' . $this->next_page . '</li>';
                    $html_page.= '<li>' . $this->pre_last . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->next_page . '</a></li>';
                    $html_page.= '<li><a  href="' . $this->page_url . $this->getTotalPage() . '">' . $this->pre_last . '</a></li>';
                }
                $html_page.= '</ul>';
                break;

            case '3':
                $html_page.= '<ul>';
                if ($this->getNowPage() <= 1) {
                    $html_page.= '<li>' . $this->pre_page . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->page_url . ($this->getNowPage() - 1) . '">' . $this->pre_page . '</a></li>';
                }
                $html_page.= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page.= '<li>' . $this->next_page . '</li>';
                    $html_page.= '<li>' . $this->pre_last . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->page_url . ($this->getNowPage() + 1) . '">' . $this->next_page . '</a></li>';
                }
                $html_page.= '</ul>';
                break;

            case '4':
                $html_page.= '<ul>';
                if ($this->getNowPage() <= 1) {
                    $html_page.= '<li>' . $this->pre_page . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() - 1) . '">' . $this->pre_page . '</a></li>';
                }
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page.= '<li>' . $this->next_page . '</li>';
                } else {
                    $html_page.= '<li><a  href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() + 1) . '">' . $this->next_page . '</a></li>';
                }
                $html_page.= '</ul>';
                break;

            case '5':
                $html_page.= '<div class="pager clearfix">';
                if ($this->getNowPage() <= 1) {
                    //首页上一页
                    $html_page.= '<a href="#">' . $this->pre_home . '</a>';
                    $html_page.= '<a href="#">' . $this->pre_page . '</a>';
                } else {
                    $html_page.= '<a href="' . $this->setShopPseudoStaticPageUrl('1') . '">' .  $this->pre_home  . '</a>';
                    $html_page.= '<a href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() - 1) . '">' . $this->pre_page . '</a>';
                }
                $html_page.= $this->getNowBar();
                if ($this->getNowPage() == $this->getTotalPage() || $this->getTotalPage() == 0) {
                    $html_page.= $this->next_page;
                    $html_page.= $this->pre_last;
                } else {
                    //下一页,末页
                    $html_page.= '<a href="' . $this->setShopPseudoStaticPageUrl($this->getNowPage() + 1) . '">' .  $this->next_page . '</a>';
                    $html_page.= '<a href="' . $this->setShopPseudoStaticPageUrl($this->getTotalPage()) . '">'  . $this->pre_last . '</a>';
                }
                $html_page.= '</div>';
                break;

            default:
                break;
        }
        /**
         * 转码
         */
        /**
         if (strtoupper(CHARSET) == 'GBK' && !empty($html_page)){
         $html_page = iconv('UTF-8','GBK',$html_page);
         }
         */
        return $html_page;
    }
    private function getNowBar() {
        /**
         * 显示效果
         * 中间显示7个，左右两个，不足则不显示省略号
         */
        /**
         * 判断当前页是否大于7
         */
        if ($this->getNowPage() >= 7) {
            /**
             * 前面增加省略号，并且计算开始页码
             */
            $begin = $this->getNowPage() - 2;
        } else {
            /**
             * 小于7，前面没有省略号
             */
            $begin = 1;
        }
        /**
         * 计算结束页码
         */
        if ($this->getNowPage() + 5 < $this->getTotalPage()) {
            /**
             * 增加省略号
             */
            $end = $this->getNowPage() + 5;
        } else {
            $end = $this->getTotalPage();
        }
        /**
         * 整理整个页码样式
         */
        $result = '';
        if ($begin > 1) {
            $result.= $this->setPageHtml(1, 1) . $this->setPageHtml(2, 2);
            $result.= $this->left_ellipsis_html . '<a>...</a>' . $this->right_ellipsis_html;
        }
        /**
         * 中间部分内容
         */
        for ($i = $begin; $i <= $end; $i++) {
            $result.= $this->setPageHtml($i, $i);
        }
        if ($end < $this->getTotalPage()) {
            $result.= $this->left_ellipsis_html . '<a>...</a>' . $this->right_ellipsis_html;
        }
        return $result;
    }
    private function setPageHtml($page_name, $page) {
        if ($this->getNowPage() == $page) {
            $result = $this->left_current_html . $page . $this->right_current_html;
        } else if (in_array($this->style, array(
            4,
            5
        ))) {
            $result = $this->left_html . "<a href='" . $this->setShopPseudoStaticPageUrl($page) . "'>" . $page_name . "</a>" . $this->right_html;
        } else {
            $result = $this->left_html . "<a href='" . $this->page_url . $page . "'>" . $page_name . "</a>" . $this->right_html;
        }
        return $result;
    }
	private function setPageUrl(){
		$uri = request_uri() ;
		$_SERVER['REQUEST_URI'] = $uri ;

		/**
		 * 不存在QUERY_STRING时
		 */
		if(empty($_SERVER['QUERY_STRING'])){
			$this->page_url = $_SERVER['REQUEST_URI']."?".$this->page_name."=";
		}else{
			if(stristr($_SERVER['QUERY_STRING'],$this->page_name.'=')){
				/**
				 * 地址存在页面参数
				 */
				$this->page_url = str_replace($this->page_name.'='.$this->now_page,'',$_SERVER['REQUEST_URI']);
				$last = $this->page_url[strlen($this->page_url)-1];
				if($last=='?' || $last=='&'){
					$this->page_url .= $this->page_name."=";
				}else{
					$this->page_url .= '&'.$this->page_name."=";
				}
			}else{
				$this->page_url = $_SERVER['REQUEST_URI'].'&'.$this->page_name.'=';
			}
		}
		return true;
	}
    private function setShopPseudoStaticPageUrl($page) {
        $param = $_GET;
        $act = ($param["act"] == "" ? "index" : $param["act"]);
        unset($param["act"]);
        $op = ($param["op"] == "" ? "index" : $param["op"]);
        unset($param["op"]);
        $param[$this->page_name] = $page;
        return urlShop($act, $op, $param);
    }
}
defined("ByShopWWI") || exit("Access Invalid!");
?>