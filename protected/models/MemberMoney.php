<?php

/**
 * This is the model class for table "member_money".
 *
 * The followings are the available columns in table 'member_money':
 * @property string $id
 * @property integer $member_id
 * @property integer $admin_id
 * @property string $inmoney
 * @property string $outmoney
 * @property string $money
 * @property string $content
 * @property integer $created
 */
class MemberMoney extends CActiveRecord
{
	public $modelName = '资金流动';
	
	public function __toString()
	{
		return $this->content;
	}
	
	protected function beforeSave()
	{
		if($this->isNewRecord)
		{
			$this->created = time();
		}
		
		return parent::beforeSave();
	}
	
    protected function afterSave()
    {
        
        $user = Member::model()->findByPk($this->member_id);
        $money = $user->balance;

        // 充值
        if( $this->inmoney )
        {
            $money = $this->inmoney + $money;
        }
        
        // 消费
        if( $this->outmoney )
        {
            $money = $money - $this->outmoney;
        }
        $this->money = $money;
        
        Member::model()->updateByPk($this->member_id, array('balance' => $money));
        self::model()->updateByPk($this->id, array('money'=>$money));



        return parent::afterSave();
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member_money';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_id, content', 'required'),
			array('member_id, admin_id, created', 'numerical', 'integerOnly'=>true),
			array('inmoney, outmoney, money', 'length', 'max'=>10),
			array('inmoney, outmoney', 'checkMoney'),
			array('content', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, member_id, admin_id, inmoney, outmoney, money, content, created', 'safe', 'on'=>'search'),
		);
	}

    public function checkMoney($attribute, $params)
    {
        $user = Member::model()->findByPk($this->member_id);
        if( $user === null )
        {
            $this->addError('member_id','该用户不存在');
        }

        if( $this->outmoney && $this->outmoney > $user->balance )
        {
            $this->addError('outmoney','余额不足，扣款失败。当前余额为：'.$user->balance);
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
			'member' => array(self::BELONGS_TO,'Member','member_id'),
			'admin' => array(self::BELONGS_TO,'Administrator','admin_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'member_id' => 'Member',
			'admin_id' => 'Admin',
			'inmoney' => '充值',
			'outmoney' => '消费',
			'money' => '余额',
			'content' => '备注',
			'created' => '生成时间',
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
		$criteria->compare('member_id',$this->member_id);
		$criteria->compare('admin_id',$this->admin_id);
		$criteria->compare('inmoney',$this->inmoney,true);
		$criteria->compare('outmoney',$this->outmoney,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created',$this->created);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page', 'pageSize'=>10),
			'sort'=>array('defaultOrder'=>'id DESC'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MemberMoney the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
