<?php

/**
 * This is the model class for table "service".
 *
 * The followings are the available columns in table 'service':
 * @property string $id
 * @property string $name
 * @property integer $shop_id
 * @property integer $city_id
 * @property string $price
 * @property string $tags
 * @property string $hint
 * @property string $awaytime
 * @property integer $photo_count
 * @property integer $base_count
 * @property string $dipian
 * @property string $content
 * @property integer $created
 * @property integer $updated
 */
class Service extends CActiveRecord
{

	public $modelName = '服务';

	public function __toString()
	{
		return $this->name;
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


	public static function dipianOptions()
	{
		return array(
			'不全送' => '不全送',
			'全送' => '全送',
		);
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, price, tags, hint, awaytime, dipian, content,shop_id', 'required'),
			array('shop_id, city_id, photo_count, base_count, created, updated', 'numerical', 'integerOnly'=>true),
			array('name, tags, hint', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			array('awaytime', 'length', 'max'=>50),
			array('dipian', 'length', 'max'=>30),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, shop_id, city_id, price, tags, hint, awaytime, photo_count, base_count, dipian, content, created, updated', 'safe', 'on'=>'search'),
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
			'name' => '服务标题',
			'shop_id' => '摄影师',
			'city_id' => '指定区域',
			'price' => '价格',
			'tags' => '标签',
			'hint' => '提示',
			'awaytime' => '拍摄时间',
			'photo_count' => '照片数',
			'base_count' => '精修片数',
			'dipian' => '底片',
			'content' => '附加说明',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('hint',$this->hint,true);
		$criteria->compare('awaytime',$this->awaytime,true);
		$criteria->compare('photo_count',$this->photo_count);
		$criteria->compare('base_count',$this->base_count);
		$criteria->compare('dipian',$this->dipian,true);
		$criteria->compare('content',$this->content,true);
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
	 * @return Service the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
