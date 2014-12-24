<?php

class MemberController extends ApiController
{


	public $allow = array('login','register','sendsms','forget');


	public function actionIndex()
	{
		$this->render('index');
	}


	/**
	 * 会员登录获取token
	 * 
	 * @return [type] [description]
	 */
	public function actionLogin()
	{
		$data = array();

		$post = CJSON::decode(file_get_contents('php://input'));
		$price = Yii::app()->config->get('order_price');


		$phone = isset($post['phone']) ? $post['phone'] : '';
		$pwd = isset($post['pwd']) ? $post['pwd'] : '';
		$appid = isset($post['appid']) ? $post['appid'] : '';
		$channel_id = isset($post['channel_id']) ? $post['channel_id'] : '';
		$user_id = isset($post['user_id']) ? $post['user_id'] : '';

		$model = Member::model()->find('phone = :phone', array(':phone'=>$phone));
		if( $model === null )
		{
			$data['message'] = '该用户并不存在，请注册';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$password = md5(md5($pwd).$model->checknum);

		if( $model->password != $password )
		{
			$data['message'] = '密码不正确';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 更新密钥
		$token = md5(md5(time()).$model->checknum);
		$updateData['token'] = $token;

		// 使用百度推送需要拿到的接口
		if($appid) $updateData['appid'] = $appid;
		if($channel_id) $updateData['channel_id'] = $channel_id;
		if($user_id) $updateData['user_id'] = $user_id;

		Member::model()->updateByPk($model->id, $updateData);

		$data['token'] = $token;
		$data['member'] = $model->attributes;

		
		$this->_sendResponse(200,CJSON::encode($data));
	}


	/**
	 * 发送验证码
	 * @return [type] [description]
	 */
	public function actionSendsms()
	{
		$data = array();
		$post = CJSON::decode(file_get_contents('php://input'));

		$phone = isset($post['phone']) ? $post['phone'] : '';
		
		// 验证手机号是否正确
        if (!preg_match("/^1[0-9]{10}$/", $phone))
        {
            $data['message'] = '手机号码格式不正确。';
            $this->_sendResponse(400,CJSON::encode($data));
        }

        // 排重
        $model = Member::model()->find('phone = :phone', array(':phone'=>$phone));
        if( $model === null )
        {
			$model = new Member();
        }else{

        	if( $model->password )
        	{
        		$data['message'] = '该手机已注册，请登录使用';
        		$this->_sendResponse(400, CJSON::encode($data));
        	}
        }

		$model->phone = $phone;
		
		if($model->save())
		{
			$message = '尊敬的客户，您的手机注册验证码：【'.$model->checknum.'】';
			// 发送短信
			SmsHelper::send($model->phone,$message);
			
			// 短信验证并自动登录
			$data['checknum'] = $model->checknum;
			$data['token'] = $model->token;

			$this->_sendResponse(200,CJSON::encode($data));
		}else{
			$data['message'] = $model->getErrors();
			$this->_sendResponse(400,CJSON::encode($data));
		}
	}


	/**
	 * 发送验证码
	 * @return [type] [description]
	 */
	public function actionSendsms2()
	{
		$data = array();
		$post = CJSON::decode(file_get_contents('php://input'));

		$phone = isset($post['phone']) ? $post['phone'] : '';
		
		// 验证手机号是否正确
        if (!preg_match("/^1[0-9]{10}$/", $phone))
        {
            $data['message'] = '手机号码格式不正确。';
            $this->_sendResponse(400,CJSON::encode($data));
        }

        // 排重
        $model = Member::model()->find('phone = :phone', array(':phone'=>$phone));
        if( $model === null )
        {
        	$data['message'] = '该用户并不存在，请注册';
        	$this->_sendResponse(400,CJSON::encode($data));
        }

		$message = '尊敬的客户，您的手机找回密码验证码：【'.$model->checknum.'】';
		// 发送短信
		SmsHelper::send($model->phone,$message);
		
		// 短信验证并自动登录
		$data['checknum'] = $model->checknum;
		
		$this->_sendResponse(200,CJSON::encode($data));
	
	}

}