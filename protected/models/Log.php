<?php

/**
 * This is the model class for table "{{log}}".
 *
 * The followings are the available columns in table '{{log}}':
 * @property integer $id
 * @property string $date
 * @property integer $time
 * @property string $ip
 * @property integer $operator_id
 * @property string $operator_name
 * @property string $type
 * @property string $category
 * @property string $description
 * @property integer $is_delete
 * @property string $model
 * @property integer $model_pk
 * @property string $model_attributes_old
 * @property string $model_attributes_new
 */
class Log extends CActiveRecord
{
	public $modelName = '系统日志';

	public $created_start, $created_end;

	public function __toString()
	{
		return $this->id;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return Log the static model class
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
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, time, type, model, model_pk, model_attributes_old, model_attributes_new', 'required'),
			array('time, operator_id, is_delete', 'numerical', 'integerOnly'=>true),
			array('ip', 'length', 'max'=>15),
			array('operator_name, type, model', 'length', 'max'=>50),
			array('category', 'length', 'max'=>40),
			array('model_pk', 'length', 'max'=>100),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, time, ip, operator_id, operator_name, type, category, description, is_delete, model, model_pk, model_attributes_old, model_attributes_new, created_start, created_end', 'safe', 'on'=>'search'),
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
			'date' => '操作日期',
			'time' => '操作时间',
			'ip' => '操作员IP',
			'operator_id' => '操作员ID',
			'operator_name' => '操作员名',
			'type' => '操作大类',
			'category' => '操作小类',
			'description' => '操作描述',
			'is_delete' => '是否已标记删除',
			'model' => '操作的模型',
			'model_pk' => '模型的主键',
			'model_attributes_old' => '旧数据',
			'model_attributes_new' => '新数据',
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

		$criteria->compare('date',$this->date,true);

		$criteria->compare('time',$this->time);

		$criteria->compare('ip',$this->ip,true);
		
		$criteria->compare('operator_id',$this->operator_id);

		$criteria->compare('operator_name',$this->operator_name,true);

		$criteria->compare('type',$this->type,true);

		$criteria->compare('category',$this->category,true);

		$criteria->compare('description',$this->description,true);

		$criteria->compare('is_delete',$this->is_delete);

		if($this->is_delete == NULL)
		{
			$criteria->compare('is_delete',0);
		}

		$criteria->compare('model',$this->model,true);

		$criteria->compare('model_pk',$this->model_pk);

		$criteria->compare('model_attributes_old',$this->model_attributes_old,true);

		$criteria->compare('model_attributes_new',$this->model_attributes_new,true);

		if($this->created_start) $criteria->compare('t.date', '>='.$this->created_start);
		if($this->created_end) $criteria->compare('t.date', '<='.$this->created_end.' 23:59:59');


		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.id DESC'),
		));
	}

	public function afterFind()
	{
		$this->description = $this->showDesc();
		return true;
	}

	public function beforeValidate()
	{
		if($this->isNewRecord)
		{
			$time = time();
			$date = date('Y-m-d H:i:s');

			if(Yii::app() instanceof CWebApplication)
			{
				$this->ip = Yii::app()->request->userHostAddress;
				$this->operator_id = Yii::app()->user->id;
				$this->operator_name = Yii::app()->user->name;
			}
			
			$this->time = $time;
			$this->date = $date;
		}
		return true;
	}

	protected function afterSave()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('time', '<'.(time()-180*24*3600));//只保留7天内的记录
		self::model()->deleteAll($criteria);

		return parent::afterSave();
	}

    /**
    * Log decode
    */
	public static function decode($json)
	{
		return CJSON::decode($json);
	}
    /**
    * Log encode
    */
	public static function encode($array)
	{
		return CJSON::encode($array);
	}

    /**
    * 封装一个对比前后数据的方法，输出HTML
    */
	public function showDesc()
	{

		$model_attributes_old = CJSON::decode($this->model_attributes_old);
		$model_attributes_new = CJSON::decode($this->model_attributes_new);

		// 日志记录的资源类 如Product、Notice
		$modelclass = $this->model;
		// 获得表的项
		$model = new $modelclass;
		$attributeLabels = $model->attributeLabels();

		if(!empty($attributeLabels))
		{
			$string .= "<TABLE class=update_log>";
			$string .= "<TBODY><TR><TH>修改项</TH><TH>旧数据</TH><TH>新数据</TH></TR>";
			foreach($attributeLabels as $key => $name)
			{
				$old_value = $model_attributes_old[$key];
				$new_value = $model_attributes_new[$key];
				$class = $old_value == $new_value ? '' : ' class="update_diff"';
				$string .= "<TR".$class."><TR><TD>$name</TD><TD>$old_value</TD><TD>$new_value</TD></TR>";
			}
			$string .= "</TBODY></TABLE>";
		}
		return $string;
	}

    /**
    * 弹出提示框
    * by biner
    */
    public function showMessage($content,$class = 'good'){

		$message = array(
			'content'=>$content,
			'class'=>$class
		);
		Yii::app()->user->setFlash('Emessage',$message);
    }
}