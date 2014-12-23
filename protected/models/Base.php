<?php

/**
 * This is the model class for table "base".
 *
 * The followings are the available columns in table 'base':
 * @property integer $id
 * @property string $name
 * @property integer $city_id
 * @property string $cate
 * @property string $image
 * @property string $content
 * @property integer $created
 */
class Base extends CActiveRecord
{

	public $modelName = '基地';


	/**
	 * 分类
	 * 
	 * @return [type] [description]
	 */
	public static function cate()
	{
		return array('实景'=>'实景','旅游景点'=>'旅游景点');
	}


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
		return 'base';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, cate, content', 'required'),
			array('city_id, created', 'numerical', 'integerOnly'=>true),
			array('name, image', 'length', 'max'=>255),
			array('cate', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, city_id, cate, image, content, created', 'safe', 'on'=>'search'),
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
			'name' => '基地名称',
			'city_id' => '指定地区',
			'cate' => '分类',
			'image' => '图片',
			'content' => '内容',
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
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('cate',$this->cate,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Base the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
