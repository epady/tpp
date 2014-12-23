<?php 
/**
 * 定义 SmsHelper 类
 *
 * @link http://caoguo.com/
 * @copyright Copyright (c) 2014 Caoguo Inc. {@link http://caoguo.com}
 * @license New BSD License {@link http://caoguo.com/license/}
 * @version $Id: SmsHelper.php 1 2014-09-21 06:04:01Z UUTAN $
 * @package helper
 */

class SmsHelper
{

	/**
	 * 汉字转码
	 * 
	 * @param  [type] $data [description]
	 * @param  string $to   [description]
	 * @return [type]       [description]
	 */
    public static function convertencoding($data, $to = 'UTF-8')
    {
        $encode_arr = array('UTF-8', 'GBK', 'GB2312', 'BIG5');
        $encoded = mb_detect_encoding($data, $encode_arr);
        $data = mb_convert_encoding($data, $to, $encoded);
        return $data;
    }



	/**
	 * 模拟POST请求发送短信
	 * 
	 * @param  [type] $mobile  [description]
	 * @param  [type] $message [description]
	 * @return [type]          [description]
	 */
	public static function send($mobile, $message)
    {
        //超长短信500个字符限制
        $messages = str_split($message, 500);
        
        foreach($messages as $content)
        {
            $url = 'http://www.lx198.com/sdk/send';
    		
            $content = $content.'【'.Yii::app()->config->get('sms_sign').'】';

            $post_data = array(); 
    		
    		$post_data['accName'] = Yii::app()->config->get('sms_user');
    		$post_data['accPwd'] = strtoupper(md5(Yii::app()->config->get('sms_pwd')));
    		$post_data['bizId'] = date('YmdHis');
    		$post_data['aimcodes'] = $mobile;
    		$post_data['content'] = self::convertencoding($content);
    		$post_data['dataType'] = 'json';
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $result = trim($result);

            self::log(var_export($result,true));
            
        }
        return true;
    }


    /**
     * 日志
     * 
     * @param type $msg
     * @throws Exception
     */
    static function log($msg,$path = 'log')
    {
        //return false;
        // 创建当前日志文件  以日期命名
        $fileName = date('Y-m-d', time()) . '.log';
        // 存放目录
        $logpath = Yii::app()->runtimePath.'/'.$path;

        if (!file_exists($logpath))
        {
            $ret = @mkdir($logpath, 0777, true);
            if (!$ret)
            {
                throw new Exception($logpath);
            }
        }
        $logfile = $logpath . '/' . $fileName;

        // 添加日志
        file_put_contents($logfile, date('Y-m-d H:i:s') . ' ' . $msg . "\n", FILE_APPEND);
    }

}