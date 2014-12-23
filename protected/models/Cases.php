<?php

/**
 * This is the model class for table "case".
 *
 * The followings are the available columns in table 'case':
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property integer $shop_id
 * @property integer $city_id
 * @property string $content
 * @property integer $created
 * @property integer $updated
 * @property string $tags
 */
class Cases extends CActiveRecord
{
	public $modelName = '案例表';

	public function __toString()
	{
		return $this->title;
	}



	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->created = $this->updated = time();
		}else{
			$this->updated = time();
		}

		$shop = Shop::model()->findByPk($this->shop_id);
		if( $shop )
		{
			$this->city_id = $shop->city_id;
		}


		return parent::beforeSave();
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'case';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, image, tags', 'required'),
			array('shop_id, city_id, created, updated', 'numerical', 'integerOnly'=>true),
			array('title, image', 'length', 'max'=>255),
			array('title, content', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, image, shop_id, city_id, content, created, updated, tags', 'safe', 'on'=>'search'),
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
			'shop' => array(self::BELONGS_TO,'Shop','shop_id'),
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
			'title' => '案例名字',
			'image' => '首图',
			'shop_id' => '所属摄影师',
			'city_id' => '指定区域',
			'content' => '描述',
			'created' => '添加时间',
			'updated' => '更新时间',
			'tags' => '标签',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);
		$criteria->compare('tags',$this->tags,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cases the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
