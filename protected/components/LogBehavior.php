<?php
/**
 * LogBehavior class file.
 *
 * @reference YiicmsActiveRecord <HTTP: www.lockphp.com yii-ar-log-auto.html nocate> <CREATED Biner by>
 *
 * @version alpha1 (2010-10-27 16:16)
 * @author Biner <HUANGHUIBIN@GMAIL.COM>
 *
 * A typical usage of LogBehavior is as follows:
 *
 * abstract class YiicmsActiveRecord extends YiicmsActiveRecord
 * {
 *		public function behaviors()
 *		{
 *			return array(
 *				// YII AR的事件行为
 *				'LogBehavior',
 *				// 时间
 *				'CTimestampBehavior' => array(
 *					'class' => 'zii.behaviors.CTimestampBehavior',
 *					'createAttribute' => 'create_time',
 *					'updateAttribute' => 'update_time',
 *				)
 *			);
 *
 *		}
 * }
 * 
 *
 */
class LogBehavior extends CActiveRecordBehavior
{
	//私有变量 保存旧数据的信息
	private $_oldattributes = array();
	
	public function afterFind($event)
	{
		//记录旧数据
		$attributes = $this->Owner->getAttributes();
		$this->setOldAttributes($attributes);
	}
	
	public function afterSave($event)
	{
		// new attributes
		$newattributes = $this->Owner->getAttributes();
		$oldattributes = $this->getOldAttributes();
		// 获得该操作资源的名称 如 ‘产品’、‘新闻’
		$resource_name = $this->Owner->modelName;
		// 获得该操作资源的类名
		$modelclass = get_class($this->Owner);
		// 获得该操作资源的主键
		$pk = $this->Owner->getPrimaryKey();
		//后台log
		$log = new Log();
		//LOG 一级大类
		$type = $resource_name.'管理';
		//LOG 二级分类
		if($this->Owner->isNewRecord)
		{
			$category = '添加'.$resource_name;
		}
		else
		{
			$category = '修改'.$resource_name;
		}
		// AR中的deleteMark()方法
		if(isset($newattributes['deleted']) && $newattributes['deleted'] == 1 AND $oldattributes['deleted'] == 0)
		{
			$category = '删除'.$resource_name;
			//处理关联数据
			$relations = $this->Owner->relations();
	        foreach($relations as $name => $relation)
	        {
	            if($relation[0] == CActiveRecord::HAS_MANY || $relation[0] == CActiveRecord::HAS_ONE)
	            {
	                $objects = $this->Owner->getRelated($name);
	                if(is_array($objects))
	                {
	                    foreach($objects as $object)
	                    {
	                        $object->deleted = 1;
	                        $object->save();
	                    }
	                }
	                elseif(is_object($objects))
	                {
	                    $objects->deleted = 1;
	                    $objects->save();
	                }
	            }
	        }
		}
		$model_attributes_old = CJSON::encode($oldattributes);
		$model_attributes_new = CJSON::encode($newattributes);
		$info = array(
			'type'=>$type,
			'category'=>$category,
			//'description'=>$description,
			'model'=>$modelclass,
			'model_pk'=>$pk,
			'model_attributes_old'=>$model_attributes_old,
			'model_attributes_new'=>$model_attributes_new,
		);
		
		$log->attributes = $info;
		
		if(!$log->save()) throw new Exception(print_r($log->errors, true));
	}
	
	//旧数据
	public function getOldAttributes()
	{
		return $this->_oldattributes;
	}
	
	public function setOldAttributes($value)
	{
		$this->_oldattributes=$value;
	}
}
