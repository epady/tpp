<?php
Yii::import('system.gii.generators.model.ModelCode');
class AdminModelCode extends ModelCode
{
	public $image_fields = array('image', 'banner', 'logo', 'avatar', 'screenshot', 'pic');
	public $file_fields = array('attch', 'file', 'upload', 'download');

	public function checkExists($name, $fields)
	{
		foreach($fields as $field)
		{
			if(strpos($name, $field) !== false)
			{
				return true;
			}
		}
		return false;
	}
	
	public function generateRules($table)
	{
		$rules=array();
		$required=array();
		$integers=array();
		$numerical=array();
		$length=array();
		$safe=array();
		$images = array();
		$files = array();
		foreach($table->columns as $column)
		{
			if($column->autoIncrement)
				continue;
			$r=!$column->allowNull && $column->defaultValue===null && $column->name != 'created' && $column->name != 'updated';
			if($r)
				$required[]=$column->name;
			if($column->type==='integer')
				$integers[]=$column->name;
			elseif($column->type==='double')
				$numerical[]=$column->name;
			elseif($column->type==='string' && $column->size>0)
				$length[$column->size][]=$column->name;
			elseif(!$column->isPrimaryKey && !$r)
				$safe[]=$column->name;

			if($column->type==='string' && $this->checkExists($column->name, $this->image_fields))
				$images[] = $column->name;
			if($column->type==='string' && $this->checkExists($column->name, $this->file_fields))
				$files[] = $column->name;
		}
		if($required!==array())
			$rules[]="array('".implode(', ',$required)."', 'required')";
		if($integers!==array())
			$rules[]="array('".implode(', ',$integers)."', 'numerical', 'integerOnly'=>true)";
		if($numerical!==array())
			$rules[]="array('".implode(', ',$numerical)."', 'numerical')";
		if($length!==array())
		{
			foreach($length as $len=>$cols)
				$rules[]="array('".implode(', ',$cols)."', 'length', 'max'=>$len)";
		}
		if($safe!==array())
			$rules[]="array('".implode(', ',$safe)."', 'safe')";

		if($images!==array())
			$rules[]="array('".implode(', ',$images)."', 'file', 'allowEmpty'=>true, 'types'=>'jpg, gif, png', 'maxSize'=>2048*1024, 'safe'=>true)";
		if($files!==array())
			$rules[]="array('".implode(', ',$files)."', 'file', 'allowEmpty'=>true, 'types'=>'doc, docx, xls, xlsx, ppt, pptx, zip, rar, 7z, pdf', 'maxSize'=>2048*1024, 'safe'=>true)";

		return $rules;
	}
}
