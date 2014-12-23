<?php

/**
 * This is the model class for table "member".
 *
 * The followings are the available columns in table 'member':
 * @property integer $id
 * @property string $phone
 * @property string $checknum
 * @property string $password
 * @property string $username
 * @property string $integral
 * @property integer $created
 * @property integer $updated
 */
class Member extends ActiveRecord
{


	public $modelName = '用户管理';


	public function __toString()
	{
		return $this->phone;
	}

	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->checknum = mt_rand(100000, 999999);
			$this->created = $this->updated = time();

			// 注册成功即返回密钥 可 注册后自动登录
			$this->token = md5(md5($this->phone).$this->checknum);

		}else{

			// 注册中途有退出
			if( !$this->password )
			{
				$this->checknum = mt_rand(100000, 999999);
				$this->created = $this->updated = time();

				// 注册成功即返回密钥 可 注册后自动登录
				$this->token = md5(md5($this->phone).$this->checknum);
			}

			$this->updated = time();
		}

		return parent::beforeSave();
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone,username,password', 'required','on'=>'insert'),
			array('phone,username', 'required','on'=>'insert'),
			//array('phone, checknum, password, pay_password, number, realname, created, updated', 'required'),
			array('phone', 'unique', 'caseSensitive' => false, 'className' => 'Member', 'criteria' => array('condition' => 'id <> ' . $this->id), 'message' => '"{value}"该手机已注册', 'on' => 'update'),
			array('email', 'unique', 'caseSensitive' => false, 'className' => 'Member', 'criteria' => array('condition' => 'id <> ' . $this->id), 'message' => '"{value}"该邮箱已存在', 'on' => 'update'),
			array('created, updated, integral', 'numerical', 'integerOnly'=>true),
			array('phone', 'length', 'max'=>11),
			array('checknum, integral', 'length', 'max'=>10),
			array('password,email', 'length', 'max'=>80),
			array('appid,user_id,channel_id', 'length', 'max'=>100),
			array('number', 'length', 'max'=>12),
			array('username', 'length', 'max'=>30),
			array('avatar,sign', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, phone, checknum, password, number, username, integral, created, updated', 'safe', 'on'=>'search'),
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
			'orders' => array(self::HAS_MANY,'Order','member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => '手机号',
			'checknum' => '验证码',
			'password' => '密码',
			'username' => '昵称',
			'integral' => '余额',
			'created' => '注册时间',
			'updated' => '更新时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('checknum',$this->checknum,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('integral',$this->integral,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Member the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
