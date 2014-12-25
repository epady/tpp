<?php

/**
 * This is the model class for table "case_photos".
 *
 * The followings are the available columns in table 'case_photos':
 * @property integer $id
 * @property integer $case_id
 * @property string $image
 * @property string $title
 * @property integer $sort_order
 * @property integer $created
 */
class CasePhotos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'case_photos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('case_id, image, title, created', 'required'),
			array('case_id, sort_order, created', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, case_id, image, title, sort_order, created', 'safe', 'on'=>'search'),
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
			'case_id' => 'Case',
			'image' => 'Image',
			'title' => 'Title',
			'sort_order' => 'Sort Order',
			'created' => 'Created',
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
		$criteria->compare('case_id',$this->case_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sort_order',$this->sort_order);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CasePhotos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
