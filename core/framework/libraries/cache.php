<?php
/**
 * 缓存操作
 *
 * @网店运维提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopwwi.com
 * @link       交流群号：111731672
 */
defined('ByShopWWI') or exit('Access Invalid!');

class Cache {

	protected $params;
	protected $enable;
	protected $handler;

	/**
	 * 实例化缓存驱动
	 *
	 * @param unknown_type $type
	 * @param unknown_type $args
	 * @return unknown
	 */
	public function connect($type,$args = array()){
		//if (empty($type)) $type = C('cache_open') ? 'redis' : 'file';
		//$type = strtolower($type);
		$type = C('cache_open') ? 'redis' : 'file';  //gyf  解决开启redis后出错
		$class = 'Cache'.ucwords($type);
		if (!class_exists($class)){
			import('cache.cache#'.$type);
		}
		return new $class($args);
	}

	/**
	 * 取得实例
	 *
	 * @return object
	 */
	public static function getInstance(){
		$args = func_get_args();
		return get_obj_instance(__CLASS__,'connect',$args);
	}
}
