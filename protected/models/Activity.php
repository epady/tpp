<?php

/**
 * This is the model class for table "activity".
 *
 * The followings are the available columns in table 'activity':
 * @property integer $id
 * @property integer $city_id
 * @property string $name
 * @property string $image
 * @property string $content
 * @property integer $status
 * @property integer $created
 */
class Activity extends CActiveRecord
{


	public $modelName = '活动';


	public function __toString()
	{
		return $this->name;
	}


	/**
	 * 状态直译
	 */
	public function statusStr()
	{
		$data = Activity::statusOptions();

		return $data[$this->status];
	}

	/**
	 * 活动状态
	 * 
	 * @return [type] [description]
	 */
	public static function statusOptions()
	{
		return array(
			'0' => '未开通',
			'1' => '活动中',
			'2' => '已完成',
		);
	}


	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->created = time();
		}

		return parent::beforeSave();
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, content', 'required'),
			array('city_id, status, created', 'numerical', 'integerOnly'=>true),
			array('name, image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, city_id, name, image, content, status, created', 'safe', 'on'=>'search'),
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
			'area' => array(self::BELONGS_TO,'Area','city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => '活动地区',
			'name' => '活动名字',
			'image' => '活动图片',
			'content' => '活动介绍',
			'status' => '活动状态',
			'statusStr' => '活动状态',
			'created' => '添加时间',
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
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Activity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
