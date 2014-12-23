<?php
/**
 * 百度推送
 * @author UUTAN <uutan@qq.com>
 */
class PushHelper
{

	/**
	 * 发送单人安卓手机通知
	 * 
	 * @param  [type] $title   [通知标题]
	 * @param  [type] $content [通知内容]
	 * @param  [type] $user_id [接收ID]
	 * @return [type]          [返回是否发送成功]
	 */
	public static function singleAndroid($title, $content, $user_id)
	{
		// 百度推送
    	$apiKey = Yii::app()->params['baidu']['appkey'];
    	$secretKey = Yii::app()->params['baidu']['secret'];

    	Yii::import('application.vendor.baiduPush');
    	$pear_path = Yii::getPathOfAlias('application.vendor.baiduPush');
    	require_once $pear_path.'/Channel.php';

        $channel = new Channel ( $apiKey, $secretKey ) ;
        $push_type = 1; //推送 单人
        //指定发到android设备
        $optional[Channel::DEVICE_TYPE] = 3;
        //指定消息类型为通知
        $optional[Channel::MESSAGE_TYPE] = 1;
        $optional[Channel::USER_ID] = $user_id;
        //通知类型的内容必须按指定内容发送，示例如下：
        $message = '{ 
                "title": "'.$title.'",
                "description": "'.$content.'",
                "notification_basic_style":7,
                "open_type":2,
                "aps": {
                    "alert":"'.$content.'",
                    "Badge":1
                }
            }';
        $message_key = "msg_key";
        return $channel->pushMessage ( $push_type, $message, $message_key, $optional ) ;
	}




}