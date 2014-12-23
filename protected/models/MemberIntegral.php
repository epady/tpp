<?php

/**
 * This is the model class for table "member_integral".
 *
 * The followings are the available columns in table 'member_integral':
 * @property integer $id
 * @property integer $user_id
 * @property integer $add
 * @property integer $remove
 * @property integer $integral
 * @property string $content
 * @property integer $created
 */
class MemberIntegral extends CActiveRecord
{

	public $modelName = '会员积分';

	public function __toString()
	{
		return $this->content;
	}


	public function beforeSave()
	{
		if( $this->isNewRecord )
		{
			$this->created = time();
		}
		return parent::beforeSave();
	}


    protected function afterSave()
    {
        
        $user = Member::model()->findByPk($this->user_id);
        $money = $user->integral;

        // 充值
        if( $this->add )
        {
            $money = $this->add + $money;
        }
        
        // 消费
        if( $this->remove )
        {
            $money = $money - $this->remove;
        }
        $this->integral = $money;
        
        Member::model()->updateByPk($this->user_id, array('integral' => $money));
        self::model()->updateByPk($this->id, array('money'=>$money));

        return parent::afterSave();
    }


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member_integral';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, content', 'required'),
			array('user_id, add, remove, integral, created', 'numerical', 'integerOnly'=>true),
			array('add, remove', 'checkMoney'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, add, remove, integral, content, created', 'safe', 'on'=>'search'),
		);
	}


    public function checkMoney($attribute, $params)
    {
        $user = Member::model()->findByPk($this->user_id);
        if( $user === null )
        {
            $this->addError('user_id','该用户不存在');
        }

        if( $this->remove && $this->remove > $user->integral )
        {
            $this->addError('remove','积分不足，扣款失败。当前积分为：'.$user->integral);
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
			'member' => array(self::BELONGS_TO,'Member','user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '用户',
			'add' => '加积分',
			'remove' => '减积分',
			'integral' => '当前积分',
			'content' => '积分介绍',
			'created' => '操作时间',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('add',$this->add);
		$criteria->compare('remove',$this->remove);
		$criteria->compare('integral',$this->integral);
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
	 * @return MemberIntegral the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
