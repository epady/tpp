<?php

/**
 * This is the model class for table "line".
 *
 * The followings are the available columns in table 'line':
 * @property integer $id
 * @property string $name
 * @property integer $area_id
 * @property string $image
 * @property string $content
 * @property integer $views
 * @property integer $created
 */
class Line extends CActiveRecord
{

	public $modelName = '线路';

	public function __toString()
	{
		return $this->name;
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
		return 'line';
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
			array('area_id, views, created', 'numerical', 'integerOnly'=>true),
			array('name, image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, area_id, image, content, views, created', 'safe', 'on'=>'search'),
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
			'area' => array(self::BELONGS_TO,'Area','area_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '线路名称',
			'area_id' => '指定显示地区',
			'image' => '首图',
			'content' => '内容',
			'views' => '查看',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('area_id',$this->area_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('views',$this->views);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Line the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
