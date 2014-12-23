<?php

class MemberController extends Controller
{


	public $allow = array('login','register','sendsms','sendsms2','forget','about','service','app');


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

		$phone = isset($post['phone']) ? $post['phone'] : '';
		$pwd = isset($post['pwd']) ? $post['pwd'] : '';

		$model = User::model()->find('mobile = :mobile', array(':mobile'=>$phone));
		if( $model === null )
		{
			$data['message'] = '该用户并不存在，请注册';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$password = md5(md5($pwd).$model->salt);

		if( $model->password != $password )
		{
			$data['message'] = '密码不正确';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 更新密钥
		$token = md5(md5(time()).$model->salt);
		$updateData['token'] = $token;
		User::model()->updateByPk($model->id, $updateData);

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

	/**
	 * 会员注册
	 * 发送验证码
	 * 
	 * @return [type] [description]
	 */
	public function actionRegister()
	{
		$data = array();
		$post = CJSON::decode(file_get_contents('php://input'));

		$phone = isset($post['phone']) ? $post['phone'] : '';
		$checknum = isset($post['checknum']) ? $post['checknum'] : '';
		$password = isset($post['password']) ? $post['password'] : '';
		$realname = isset($post['realname']) ? $post['realname'] : '';
		$number = isset($post['number']) ? $post['number'] : '';

		$appid = isset($post['appid']) ? $post['appid'] : '';
		$channel_id = isset($post['channel_id']) ? $post['channel_id'] : '';
		$user_id = isset($post['user_id']) ? $post['user_id'] : '';
		
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
        	$data['message'] = '请发送短信验证码来注册会员';
        	$this->_sendResponse(400,CJSON::encode($data));
        }

        // 是否已注册
        if( $model === true && $model->password )
        {
        	$data['message'] = '该手机已注册，请登录';
        	$this->_sendResponse(400, CJSON::encode($data));
        }

        // 验证短信
        if( $checknum != $model->checknum )
        {
        	$data['message'] = '短信验证不正确，请查收最新短信';
        	$this->_sendResponse(400,CJSON::encode($data));
        }

        // 验证密码
        if( strlen($password) < 6 || strlen($password) > 18 )
        {
        	$data['message'] = '密码格式为大于6位或小于18位的字母和数字组合';
        	$this->_sendResponse(400,CJSON::encode($data));
        }

        $model->password = md5(md5($password).$model->checknum);
		$model->realname = $realname;
		$model->number = $number;

		if($appid)  $model->appid = $appid;
		if($channel_id) $model->channel_id = $channel_id;
		if($user_id) $model->user_id = $user_id;
		
		if($model->save())
		{
			// 活动
			$register_price = Yii::app()->config->get('register_price');
			if( $register_price && is_numeric($register_price) )
			{
				$money = new MemberMoney();
				$money->member_id = $model->id;
				$money->inmoney = $register_price;
				$money->content = '新用户注册，系统赠送';
				$money->save();
			}
			
			$price = Yii::app()->config->get('order_price');
			
			$data['token'] = $model->token;
			$data['member'] = $model->attributes;
			$data['price'] = number_format($price,2);

			$this->_sendResponse(200,CJSON::encode($data));
		}else{
			$data['message'] = $model->getErrors();
			$this->_sendResponse(400,CJSON::encode($data));
		}
	}


	/**
	 * 退出会员清空token
	 * 
	 * @return [type] [description]
	 */
	public function actionLogout()
	{
		$member = $this->member;

		Member::model()->updateByPk($member['id'], array('token'=>''));

		$data['message'] = '退出成功';

		$this->_sendResponse(200,CJSON::encode($data));	
	}


	/**
	 * 获取用户资料
	 * 
	 * @return [type] [description]
	 */
	public function actionProfile()
	{

		$data['member'] = $this->member;
		$data['price'] = Yii::app()->config->get('order_price');

		$this->_sendResponse(200,CJSON::encode($data));
	}


	/**
	 * 更新用户信息
	 * 
	 * @return [type] [description]
	 */
	public function actionUpdate()
	{
		$post = CJSON::decode(file_get_contents('php://input'));

		$number = isset($post['number']) ? trim($post['number']) : '';
		$realname = isset($post['realname']) ? trim($post['realname']) : '';

		$member = $this->member;
		$model = Member::model()->findByPk($member['id']);
		if( $model === null )
		{
			$data['message']  = '该用户资料不存在';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$model->number = $number;
		$model->realname = $realname;

		if( $model->save() )
		{
			$data['member'] = $model->attributes;
			$this->_sendResponse(200,CJSON::encode($data));
		}else{
			$data['message'] = $model->getErrors();
			$this->_sendResponse(400,CJSON::enocde($data));
		}
	}


	/**
	 * 更新密码
	 * @return [type] [description]
	 */
	public function actionPassword()
	{
		$post = CJSON::decode(file_get_contents('php://input'));

		$old_password = isset($post['old_password']) ? trim($post['old_password']) : '';
		$password = isset($post['password']) ? trim($post['password']) : '';
		$chk_password = isset($post['chk_password']) ? trim($post['chk_password']) : '';

		$member = $this->member;
		$model = Member::model()->findByPk($member['id']);
		if( $model === null )
		{
			$data['message']  = '该用户资料不存在';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 两新密码是否一致
		if( $password != $chk_password )
		{
			$data['message'] = '两次新密码输入不一致。';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 验证旧密码
		if( $model->password != md5(md5($old_password).$model->checknum) )
		{
			$data['message'] = '现密码验证不正确，请输入正确的密码。';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 新旧密码是否一致
		if( md5(md5($password).$model->checknum) == $model->password )
		{
			$data['message'] = '新密码不能与旧密码一致。';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$model->password = md5(md5($password).$model->checknum);

		if( $model->save() )
		{
			$data['message'] = '密码修改成功，下回密码请输入新密码';
			$this->_sendResponse(200,CJSON::encode($data));
		}else{
			$data['message'] = $model->getErrors();
			$this->_sendResponse(400,CJSON::enocde($data));
		}
	}


	/**
	 * 找回密码
	 * @return [type] [description]
	 */
	public function actionForget()
	{
		$post = CJSON::decode(file_get_contents('php://input'));

		$phone = isset($post['phone']) ? trim($post['phone']) : '';
		$checknum = isset($post['checknum']) ? trim($post['checknum']) : '';
		$password = isset($post['password']) ? trim($post['password']) : '';
		$chk_password = isset($post['chk_password']) ? trim($post['chk_password']) : '';

		
		$model = Member::model()->find('phone = :phone', array(':phone'=>$phone));
		if( $model === null )
		{
			$data['message']  = '该用户资料不存在';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 两新密码是否一致
		if( $password != $chk_password )
		{
			$data['message'] = '两次新密码输入不一致。';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 验证短信码
		if( $checknum != $model->checknum )
		{
			$data['message'] = '验证密码不正确';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 新旧密码是否一致
		if( md5(md5($password).$model->checknum) == $model->password )
		{
			$data['message'] = '新密码不能与旧密码一致。';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$model->password = md5(md5($password).$model->checknum);

		if( $model->save() )
		{
			$data['message'] = '密码修改成功，下回密码请输入新密码';
			$this->_sendResponse(200,CJSON::encode($data));
		}else{
			$data['message'] = $model->getErrors();
			$this->_sendResponse(400,CJSON::enocde($data));
		}
	}


	/**
	 * 获取财务信息
	 * @return [type] [description]
	 */
	public function actionMoney()
	{
		$member = $this->member;

		$criteria = new CDbCriteria();  
		$criteria->compare('member_id', $member['id']);
		$criteria->order = 'id DESC';

		$count = MemberMoney::model()->count($criteria);  
		$pages = new CPagination($count);  

		// 返回前一页  
		$pages->pageSize=10;  
		$pages->applyLimit($criteria);  
		$models = MemberMoney::model()->findAll($criteria);  

		$moeny = array();
		foreach($models as $model)
		{
			$moeny[] = array(
				'id' => $model->id,
				'inmoney' => $model->inmoney != '0.00' ? number_format($model->inmoney,2) : '-',
				'outmoney' => $model->outmoney != '0.00' ? number_format($model->outmoney,2) : '-',
				'money' => number_format($model->money,2),
				'content' => $model->content,
				'created' => date('Y-m-d',$model->created)
			);
		}

		$data['list'] = $moeny;
		$this->_sendResponse(200, CJSON::encode($data));
	}


	/**
	 * 财务信息
	 * @return [type] [description]
	 */
	public function actionMoneyview()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
		$member = $this->member;

		$model = MemberMoney::model()->findByPk($id, 'member_id = :member_id', array(':member_id'=>$member['id']));

		if( $model === null )
		{
			$data['message'] = '该资料不存在，或您没有权限访问';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$data = array(
			'id' => $model->id,
			'inmoney' => $model->inmoney != '0.00' ? number_format($model->inmoney,2) : '-',
			'outmoney' => $model->outmoney != '0.00' ? number_format($model->outmoney,2) : '-',
			'money' => number_format($model->money,2),
			'content' => $model->content,
			'created' => date('Y-m-d',$model->created)
		);

		$this->_sendResponse(200,CJSON::encode($data));
	}


	/**
	 * 设备运营人员
	 * 
	 * @return [type] [description]
	 */
	public function actionService()
	{
		$cr = new CDbCriteria;
		$cr->order = 'id ASC';
		$models = Service::model()->findAll($cr);
		$data = array();
		foreach($models as $item)
		{
			$data[$item->name] = $item->name;
		}
		$list['list'] = $data;
		$this->_sendResponse(200,CJSON::encode($list));
	}


	/**
	 * 获取关于我们数据
	 * 
	 * @return [type] [description]
	 */
	public function actionAbout()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$model = About::model()->findByPk($id);

		if( $model === null )
		{
			$data['message'] = '该资料不存在';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		$data = array(
			'title' => $model->title,
			'content' => $model->desc
		);

		$this->_sendResponse(200,CJSON::encode($data));
		
	}

	/**
	 * APP版本更新
	 * 
	 * @return [type] [description]
	 */
	public function actionApp()
	{
		$app_version = Yii::app()->config->get('app_version');
		$app_url = Yii::app()->config->get('app_fileurl');
		$app_message = Yii::app()->config->get('app_message');

		$data['app'] = array(
			'version' => $app_version,
			'url' => $app_url,
			'message' => $app_message,
		);

		$this->_sendResponse(200, CJSON::encode($data));
	}	

}