<?php
/**
 * 队列
*
 * 
 *
 *
 * @网店运维提供技术支持 授权请购买shopnc授权
 * @license    http://www.shopwwi.com
 * @link       交流群号：111731672
*/
defined('ByShopWWI') or exit('Access Invalid!');
ini_set('default_socket_timeout', -1);
class queueControl extends BaseCronControl {

    public function __construct() {}

    public function indexOp() {

        function yf_queue_log($file,$txt)
        {
            $fp =  fopen($file,'ab+');
            //gmdate('Y-m-d H:i:s', time() + 3600 * 8)
            fwrite($fp,'-----------'.date('Y-m-d H:i:s', time()).'-----------------');
            fwrite($fp,$txt);
            fwrite($fp,"\r\n\r\n\r\n");
            fclose($fp);
        }

        $logic_queue = Logic('queue');
        $model = Model();
        $worker = new QueueServer();
        $queues = $worker->scan();
        while (true) {
            $content = $worker->pop($queues,600);
            if (is_array($content)) {
                $method = key($content);
                $arg = current($content);

              $log_content=['method'=>$method,'arg'=>$arg];
              yf_queue_log('/home/yefeng/yf-queue-log.txt',var_export($log_content,true));

                $result = $logic_queue->$method($arg);
                //if (!empty($result['state'])) {
                //    $this->log($result['msg'],false);
                //}
                 $log_content2=['method'=>$method,'arg'=>$arg,'result'=>$result];
                 yf_queue_log('/home/yefeng/yf-queue-log.txt',var_export($log_content2,true));
            } else {
                $model->checkActive();
            }
        }
    }
}
