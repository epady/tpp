<?php
/**
 * 
 * 后台用户表模型
 * 
 */
class Administrator extends CActiveRecord
{
    public $modelName = '后台用户';
    
	/**
	 * The followings are the available columns in table '{{user}}':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $role
	 */
    public function getRoles()
    {
        return Yii::app()->authManager->getRoles($this->username);
    }

    public function getRoleName()
    {
    	$roles = Administrator::roleList();
    	if( isset($roles[$this->role]) )
    	{
    		return  $roles[$this->role];
    	}
    	return $this->role;
    }

    /**
     * 角色列表
     * 
     * @return [type] [description]
     */
    public static function roleList()
    {
    	return array(
    		'Admin' => '管理员',
    		'pay' 	=> '充值员',
    		'pre'	=> '预录员',
    	);
    }

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{administrator}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, role, email', 'required'),
			array('password', 'required', 'on'=>'insert'),
			array('order_id,order_status,amount', 'numerical', 'integerOnly'=>true),
			array('username, password, email, pay_password', 'length', 'max'=>128),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, email, role', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'password' => '密码',
			'pay_password' => '支付密码',
			'amount' => '额度',
			'email' => 'Email',
			'last_login_time' => '最近登录时间',
			'this_login_time' => '当前登录时间',
			'last_login_ip' => '最近登录IP',
			'this_login_ip' => '当前登录IP',
			'role' => '角色',
			'roles' => '角色',
			'roleName' => '角色',
			'order_id' => '当前订单',
			'order_status' => '处理订单状态',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);

		$criteria->compare('username',$this->username,true);

		$criteria->compare('password',$this->password,true);

		$criteria->compare('email',$this->email,true);
		$criteria->compare('role',$this->role);

		$data = new CActiveDataProvider(get_class($this), array(
                    'criteria'=>$criteria,
                ));
       $data->pagination->pageSize = 10;
		return $data;
	}
}