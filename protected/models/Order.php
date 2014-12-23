<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $id
 * @property integer $member_id
 * @property string $image
 * @property string $sn
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 */
class Order extends CActiveRecord
{

	public $modelName = '订单';

	public function behaviors()
	{
		return array(
			'LogBehavior',
		);
	}


	public function getMessage()
	{
		switch ($this->status) {
			case '0':
				$message = '等待预录人员审核';
				break;
			case '1':
				$message = '预录人员正在处理该订单';
				break;
			case '2':
				$message = '订单异常，请重新上传图片';
				break;
			case '3':
				$message = '您已修改并提交，等待预录人员处理';
				break;
			case '4':
				$message = '订单处理完成，3小时内无异议即结束订单';
				break;
			case '5';
				$message = '订单已结束。';
				break;
			case '6':
				$message = '订单取消';
				break;
			default:
				$message = '未知状态';
				break;
		}
		return $message;
	}

	/**
	 * 列表用图
	 * 
	 * @return [type] [description]
	 */
	public function getImageStr()
	{
		// 默认图
		$defaultUrl = Yii::app()->request->getBaseUrl(true).'/images/300.jpg';
		$imageFileName = realpath(Yii::app()->basePath.'/..').$this->image;
		if(!file_exists($imageFileName)){
			 return $defaultUrl;
		}
		$thumbFileName = $imageFileName.'_min.jpg';
		if(!file_exists($thumbFileName))
		{
			$image = ImageHelper::createFromFile($imageFileName);
			if(!$image)
			{
				return $defaultUrl;
			}
			$image->crop(300,300);
			$image->save($thumbFileName);
		}
		return Yii::app()->request->getBaseUrl(true).$this->image.'_min.jpg';
	}

	/**
	 * 回复用图
	 * 
	 * @return [type] [description]
	 */
	public function getReImageStr()
	{
		// 默认图
		return $this->reimage ? Yii::app()->request->getBaseUrl(true).$this->reimage : '';
	}

	/**
	 * 获取当前最大值
	 * 
	 * @return [type] [description]
	 */
	public static function maxId()
	{
		$sql = 'SELECT MAX(id) + 1 FROM `order`';
		return Yii::app()->db->createCommand($sql)->queryScalar();
	}

	/**
	 * 生成SN
	 * 
	 * @return [type] [description]
	 */
	public function createSN()
	{
		$maxId = self::maxId();
		$sn = str_repeat('0', 6 - strlen($maxId)) . $maxId;
		
		$sql = "SELECT sn FROM `order` WHERE sn LIKE '".$sn."%' ORDER BY LENGTH(sn) DESC";
		$sn_list = Yii::app()->db->createCommand($sql)->queryColumn();
		
		if( in_array( $sn, $sn_list) )
		{
			$max = pow(10, strlen($sn_list[0]) - strlen($sn) +1) - 1;
			$new_sn = $sn.mt_rand(0, $max);
			while ( in_array($new_sn, $sn_list)) {
				$new_sn = $sn . mt_rand(0, $max);
			}
			$sn = $new_sn;
		}
		return $sn;
	}

	public static function createSN2($maxId)
	{
		$sn = str_repeat('0', 6 - strlen($maxId)) . $maxId;
		$sql = "SELECT sn FROM `order` WHERE sn LIKE '".$sn."%' ORDER BY LENGTH(sn) DESC";
		$sn_list = Yii::app()->db->createCommand($sql)->queryColumn();
		
		if( in_array( $sn, $sn_list) )
		{
			$max = pow(10, strlen($sn_list[0]) - strlen($sn) +1) - 1;
			$new_sn = $sn.mt_rand(0, $max);
			while ( in_array($new_sn, $sn_list)) {
				$new_sn = $sn . mt_rand(0, $max);
			}
			$sn = $new_sn;
		}
		return $sn;
	}

	/**
	 * 订单生成SN
	 * 
	 * @return [type] [description]
	 */
	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->created = $this->updated = time();
		}else{
			$this->updated = time();
		}

		if( empty($this->sn) )
		{
			$this->sn = $this->createSN();
		}

		return parent::beforeSave();
	}


	/**
	 * 修改订单成功后，即时更新会员订单状态
	 * 
	 * @return [type] [description]
	 */
	public function afterSave()
	{
		// 即时更新订单状态 ，订单处理完成
		if( $this->status == 5 ) 
		{
			$order_id = 0;
			$order_status = 0;	
		}else{
			$order_id = $this->id;
			$order_status = $this->status;
		}
		Member::model()->updateByPk($this->member_id, array('order_id'=>$order_id, 'order_status'=>$order_status));
		$title = '';
		// 审核通知
		if( $this->status == 2 )
		{
			$title = '订单异常通知';			
		}
		elseif($this->status == 4 )
		{
			$title = '订单处理完成';
		}
		elseif( $this->status == 5 )
		{
			$title = '订单结束，您又可以新增预录啦！';
		}

		$content = $this->getMessage();
		
		// 发送
		if( $this->member->user_id && $title )
		{
			PushHelper::singleAndroid($title, $content, $this->member->user_id);
		}

		return parent::afterSave();
	}

	/**
	 * 订单状态列表
	 * 
	 * @return [type] [description]
	 */
	public static function statusArray()
	{
		return array(
			'0' => '新建订单',
			'1' => '处理订单',
			'2' => '订单异常',
			'3' => '修改提交',
			'4' => '已完成',
			'5' => '订单结束',
			'6' => '订单取消',
		);
	}

	/**
	 * 状态
	 * 
	 * @return [type] [description]
	 */
	public function getStatusStr()
	{
		$status = Order::statusArray();
		if( isset($status[$this->status]) )
		{
			return $status[$this->status];	
		}else{
			return '';
		}
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('image, service, member_id', 'required'),
			array('member_id,is_diff,is_ass, status, created, updated', 'numerical', 'integerOnly'=>true),
			array('image,reimage', 'length', 'max'=>255),
			array('sn,service, gw', 'length', 'max'=>20),
			array('content,receipt', 'length', 'max'=>2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, member_id,is_diff,is_ass, image, content, sn, status, created, updated', 'safe', 'on'=>'search'),
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
			'member' => array(self::BELONGS_TO,'Member','member_id'),
			'logs' => array(self::HAS_MANY,'OrderLog','order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'member_id' => '会员名称',
			'image' => '图片',
			'reimage' => '回执图片',
			'sn' => '订单号',
			'status' => '订单状态',
			'statusStr' => '订单状态',
			'order_price' => '订单金额',
			'is_diff' => '异议状态',
			'content' => '异议补充',
			'created' => '生成时间',
			'updated' => '修改时间',
			'is_ass' => '是否指定',
			'receipt' => '回执',
			'service' => '营运人',
			'gw' => '皮重',
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
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_diff',$this->is_diff);
		$criteria->compare('created',$this->created);
		$criteria->compare('updated',$this->updated);
		$criteria->order = "id DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
