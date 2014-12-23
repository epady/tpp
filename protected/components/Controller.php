<?php

class Controller extends CController
{

	public $breadcrumbs = array();

	/**
	 * 认证通过后的用户数据
	 * @var array
	 */
	public $member = array();

	/**
	 * 不需要认证的动作集合
	 * @var array
	 */
	public $allow = array();

	/**
	 * 验证订单信息
	 * 
	 * @return [type] [description]
	 */
	public function beforeAction($action)
	{
		$data = array();
		if( isset($_GET['token']) && !empty($_GET['token']) )
		{
			$token = trim($_GET['token']);
			$member = Member::model()->find('token = :token', array(':token'=>$token));
			if( $member )
			{
				$this->member = $member->attributes;
			}else{
				$data['message'] = '您的密钥失效，请重新登录';
				$data['action'] = 'logout';
			}
		}else{
			$data['message'] = '请登录后再操作:'.$this->action->id;
			$data['action'] = 'login';
		}
		
		if( isset($data['message']) && !in_array($this->action->id, $this->allow))
		{
			$this->_sendResponse(403,CJSON::encode($data));
		}

		return parent::beforeAction($action);
	}

	/**
	 * 头信息内容
	 * 
	 * @param  integer $status       [description]
	 * @param  string  $body         [description]
	 * @param  string  $content_type [description]
	 * @return [type]                [description]
	 */
	public function _sendResponse($status = 200, $body = '', $content_type = 'application/json')
	{
		header('Access-Control-Allow-Origin: *');
		$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
		header($status_header);
		header('Content-type: ' . $content_type);
		echo $body;
		Yii::app()->end();
	}


	/**
	 * 状态码解释
	 * 
	 * @param  [type] $status [description]
	 * @return [type]         [description]
	 */
	public function _getStatusCodeMessage($status)
	{
	  $codes = Array(
	    200 => 'OK',
	    400 => 'Bad Request',
	    401 => 'Unauthorized',
	    402 => 'Payment Required',
	    403 => 'Forbidden',
	    404 => 'Not Found',
	    500 => 'Internal Server Error',
	    501 => 'Not Implemented',
	  );
	  return (isset($codes[$status])) ? $codes[$status] : '';
	}
}