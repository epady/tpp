<?php

/**
 * 后台充值模型
 * 
 */
class PayForm extends CFormModel
{
	public $phone;
	public $money;
	public $password;
	public $content;
    public $verifyCode;
    public $member_id;
    public $admin_id;
	private $_identity;

	/**
	 * 验证规则
	 */
	public function rules()
	{
		return array(
			array('phone, money, password, content, verifyCode', 'required'),
			array('money,admin_id', 'numerical', 'integerOnly'=>true),
			array('money', 'checkMoney'),
			array('phone', 'checkPhone'),
			array('password', 'checkPassword'),
			array('content','length','max'=>'255'),
            array('verifyCode', 'captcha', 'allowEmpty' => CCaptcha::checkRequirements()),
		);
	}

	/**
	 * 检验充值账号
	 * @param  [type] $attribute [description]
	 * @param  [type] $params    [description]
	 * @return [type]            [description]
	 */
	public function checkMoney($attribute, $params)
	{
        $admin = Administrator::model()->findByPk($this->admin_id);
        if( $admin && $admin->role == 'pay' )
        {
        	if( $this->money > $admin->amount )
        	{
        		$this->addError('money','您的授权额度不足，请联系管理员。当前剩余额度为：'.number_format($admin->amount,2));
        	}
        }else{
        	$this->addError('money','您不是充值人员，无法进行充值。');
        }
	}

	/**
	 * 检验充值用户是否存在
	 * 
	 * @param  [type] $attribute [description]
	 * @param  [type] $params    [description]
	 * @return [type]            [description]
	 */
	public function checkPhone($attribute, $params)
	{
		$model = Member::model()->find('phone = :phone', array(':phone'=>$this->$attribute));
		if( $model === null )
		{
			$this->addError('phone','该用户不存在,请确认输入.');
		}
		$this->member_id = $model->id;
	}

	/**
	 * 检测充值密码是否正确
	 * 
	 * @param  [type] $attribute [description]
	 * @param  [type] $params    [description]
	 * @return [type]            [description]
	 */
	public function checkPassword($attribute, $params)
	{
		$model = Administrator::model()->findByPk(Yii::app()->user->id);

		$password = md5(md5($this->password).$model->salt);

		if( $password != $model->pay_password )
		{
			$this->addError('password','充值密码不正确，无法充值');
		}

	}

	public function save()
	{
		if( empty($this->member_id) )
		{
			$this->addError('phone','该手机号用户不存在');
		}

		$model = new MemberMoney;
		$model->member_id = $this->member_id;
		$model->inmoney = $this->money;
		$model->admin_id = Yii::app()->user->id;
		$model->content = $this->content;
		if($model->save())
		{
			// 扣除充值人员额度
            $admin = Administrator::model()->findByPk($this->admin_id);
            if( $admin )
            {
            	$amount = $admin->amount - $this->money;
            	Administrator::model()->updateByPk($admin->id, array('amount'=>$amount));
            }
            return true;
		}
	}


	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'phone'=>'充值手机号',
            'money'=>'充值金额',
			'password'=>'充值密码',
			'content' => '充值备注',
            'verifyCode'=>'验证码',
		);
	}


}
