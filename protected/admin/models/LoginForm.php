<?php

/**
 * 后台登录模型
 * 
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
    public $verifyCode;

	private $_identity;

	/**
	 * 验证规则
	 */
	public function rules()
	{
		return array(
			array('username, password', 'required'),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
           // array('verifyCode', 'captcha', 'allowEmpty'=> !extension_loaded('gd')),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'username'=>'用户名',
            'password'=>'密码',
			'rememberMe'=>'记住并自动登录',
            'verifyCode'=>'验证码',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
			switch($this->_identity->errorCode)
			{
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->addError('username','用户名不正确.');
					break;
				case UserIdentity::ERROR_PASSWORD_INVALID:
					$this->addError('password','密码不正确.');
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
