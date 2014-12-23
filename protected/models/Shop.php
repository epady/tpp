<?php

/**
 * This is the model class for table "shop".
 *
 * The followings are the available columns in table 'shop':
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $password
 * @property string $email
 * @property integer $city_id
 * @property string $avatar
 * @property string $image
 * @property string $sign
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 */
class Shop extends CActiveRecord
{

	public $modelName = '摄影师';

	public function __toString()
	{
		return $this->name;
	}

	/**
	 * 认证状态
	 * 
	 * @return [type] [description]
	 */
	public static function statusOptions()
	{
		return array(
			'0' => '未认证',
			'1' => '认证中',
			'2' => '已认证',
			'3' => '已冻结',
		);
	}


	/**
	 * 时间处理
	 * 
	 * @return [type] [description]
	 */
	public function beforeSave()
	{
		if ( $this->isNewRecord )
		{
			$this->created = $this->updated = time();
		}else{
			$this->updated = time();
		}
		return parent::beforeSave();
	}

	/**
	 * 同步更新该商家的案例显示区域
	 * 
	 * @return [type] [description]
	 */
	public function afterSave()
	{
		Cates::model()->updateAll(array('city_id'=>$this->city_id), 'shop_id = :shop_id', array(':shop_id'=>$this->id));
		
		return parent::afterSave();	
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, phone, password, email', 'required','on' => 'insert'),
			array('city_id, company_id, status, created, updated', 'numerical', 'integerOnly'=>true),
			array('name, email', 'length', 'max'=>60),
			array('phone', 'length', 'max'=>11),
			array('password', 'length', 'max'=>90),
			array('avatar, image, sign', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, phone, password, email, city_id, company_id, avatar, image, sign, status, created, updated', 'safe', 'on'=>'search'),
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
			'name' => '用户名',
			'phone' => '手机号',
			'password' => '密码',
			'email' => '邮箱',
			'city_id' => '地区',
			'company_id' => '组织机构',
			'avatar' => '头像',
			'image' => '背景',
			'sign' => '签名',
			'status' => '认证状态',
			'created' => '添加时间',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('sign',$this->sign,true);
		$criteria->compare('status',$this->status);
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
	 * @return Shop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
