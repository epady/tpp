<?php

/**
 * This is the model class for table "schedule".
 *
 * The followings are the available columns in table 'schedule':
 * @property integer $id
 * @property integer $shop_id
 * @property string $date
 * @property integer $user_id
 * @property integer $service_id
 * @property integer $created
 * @property integer $updated
 */
class Schedule extends CActiveRecord
{

	public $modelName = '预约';


	public function __toString()
	{
		return $this->date;
	}



	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->created = $this->updated = time();
		}else{
			$this->updated = time();
		}

		$service = Service::model()->findByPk($this->service_id);
		if( $service )
		{
			$this->shop_id = $service->shop_id;
		}

		return parent::beforeSave();
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'schedule';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, user_id, service_id', 'required'),
			array('date','checkDate'),
			array('user_id','checkUser'),
			array('shop_id, user_id, service_id, created, updated', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shop_id, date, user_id, service_id, created, updated', 'safe', 'on'=>'search'),
		);
	}



	public function checkDate($attribute, $params)
	{
		$cr = new CDbCriteria;
		$cr->compare('date',$this->date);
		$cr->compare('service_id',$this->service_id);
		$models = Schedule::model()->find($cr);
		if( $models )
		{
			$this->addError('date', $this->date.'已被预约，请重新选择日期');
		}
	}


	public function checkUser($attribute, $params)
	{
		$member = Member::model()->findByPk($this->user_id);
		if( $member === null )
		{
			$this->addError('user_id','该用户不存在');
		}
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'shop' => array(self::BELONGS_TO,'Shop','shop_id'),
			'member' => array(self::BELONGS_TO,'Member','user_id'),
			'service' => array(self::BELONGS_TO,'Service','service_id'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shop_id' => '指定摄影师',
			'date' => '预约日期',
			'user_id' => '用户ID',
			'service_id' => '预约服务',
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
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('service_id',$this->service_id);
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
	 * @return Schedule the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
