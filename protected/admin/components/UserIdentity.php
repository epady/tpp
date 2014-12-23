<?php
/**
 *  后台用户登录验证参处理类
 *
 *  
 */
class UserIdentity extends CUserIdentity
{   
	private $_id;
	
	
	/**
	 * 
	 * 重写验证
	 *
	 * 密码是否需要加盐（salt）
	 * 
	 * @return [type] [description]
	 */
	public function authenticate()
	{
	    
        $user = Administrator::model()->findByAttributes(array('username'=>$this->username));
		if($user === null)
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else if($user->password != md5(md5($this->password).$user->salt))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else
        {
        	$this->_id=$user->id;
			$this->username=$user->username;
            $this->username = $user->username;

         	$user->last_login_time = $user->this_login_time;
            $user->this_login_time = time();
            $user->last_login_ip = $user->this_login_ip;
            $user->this_login_ip = Yii::app()->getRequest()->userHostAddress;
            $user->save(false);


            $this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}
	


	/**
	 * 
	 * @return 登录成功后返回用户的id
	 */
	public function getId()
	{
		return $this->_id;
	}

}