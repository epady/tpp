<?php
/**
 * 重写生成器
 * 
 */

Yii::import('system.gii.generators.crud.CrudCode');

class AdminCrudCode extends CrudCode
{
	public $generateComponents = true;
	public $generateLayouts = true;
	public $layoutPrefix = '';
	public $theme = 'default';

	// 常用的图片类型字段
	public $image_fields = array('image', 'banner', 'logo', 'avatar', 'screenshot', 'pic');
	// 常用上传字段
	public $file_fields = array('attch', 'file', 'upload', 'download');
	// 常用网址字段 
	public $url_fields = array('url', 'link', 'path', 'site');
	// 常用编辑器字段 （根据类名自动绑定编辑器）
	public $editor_fields = array('content', 'desc', 'message');

	/**
	 * 验证字段是否存在
	 * 
	 * @param  [type] $name   [description]
	 * @param  [type] $fields [description]
	 * @return [type]         [description]
	 */
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

	/**
	 * 规则
	 * 
	 * @return [type] [description]
	 */
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('layoutPrefix', 'filter', 'filter' => 'trim'),
			array('generateComponents, generateLayouts, layoutPrefix, theme', 'sticky'),
		));
	}


	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'generateComponents' => 'Generate Components and Widgets',
			'generateLayouts' => 'Generate Layouts',
			'layoutPrefix' => 'Layout Prefix',
			'theme' => 'Theme',
		));
	}

	public function requiredTemplates()
	{
		return array_merge(parent::requiredTemplates(), array(
			// 'layouts' . DIRECTORY_SEPARATOR . 'main.php',
		));
	}


	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;
		$controllerTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'controller.php';

		$this->files[]=new CCodeFile(
			$this->controllerFile,
			$this->render($controllerTemplateFile)
		);

		$files=scandir($templatePath);
		foreach($files as $file)
		{
			if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php' && $file!=='controller.php')
			{
				$this->files[]=new CCodeFile(
					$this->viewPath.DIRECTORY_SEPARATOR.$file,
					$this->render($templatePath.'/'.$file)
				);
			}
		}
	}

	public function getLayoutPath()
	{
		return rtrim($this->getModule()->getLayoutPath() . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $this->layoutPrefix), DIRECTORY_SEPARATOR);
	}

	public function getRelativeLayoutPath()
	{
		return rtrim('layouts/' . str_replace('.', '/', $this->layoutPrefix), '/');
	}

	public function generateInputLabel($modelClass, $column)
	{
		return "CHtml::activeLabelEx(\$model, '{$column->name}', array('class' => 'label'))";
	}

	public function generateActiveLabel($modelClass, $column)
	{
		return "\$form->labelEx(\$model, '{$column->name}', array('class' => 'label'))";
	}

	public function generateInputField($modelClass, $column)
	{
		if ($column->type === 'boolean')
		{
			return "CHtml::activeCheckBox(\$model, '{$column->name}', array('class' => 'label'))";
		}
		else
		{
			if (stripos($column->dbType, 'text') !== false)
			{
				return "CHtml::activeTextArea(\$model, '{$column->name}', array('rows' => 6, 'cols' => 50, 'class' => 'text_area'))";
			}
			else
			{
				if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
				{
					$inputField = 'activePasswordField';
				}
				else
				{
					$inputField = 'activeTextField';
				}
				if ($column->type !== 'string' || $column->size === null)
				{
					return "CHtml::{$inputField}(\$model, '{$column->name}', array('class' => 'text_field'))";
				}
				else
				{
					if (($size = $maxLength = $column->size) > 60)
					{
						$size = 60;
					}
					return "CHtml::{$inputField}(\$model, '{$column->name}', array('size' => {$size}, 'maxlength' => {$maxLength}, 'class' => 'text_field'))";
				}
			}
		}
	}

	public function generateActiveField($modelClass, $column)
	{
		if ($column->type === 'boolean')
		{
			return "\$form->checkBox(\$model, '{$column->name}')";
		}
		else
		{
			if (stripos($column->dbType, 'text') !== false)
			{
				return "\$form->textArea(\$model, '{$column->name}', array('rows' => 6, 'cols' => 50))";
			}
			else
			{
				if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
				{
					$inputField = 'passwordField';
				}
				else
				{
					$inputField = 'textField';
				}
				if ($column->type !== 'string' || $column->size === null)
				{
					return "\$form->{$inputField}(\$model, '{$column->name}', array('class' => 'text_field'))";
				}
				else
				{
					if (($size = $maxLength = $column->size) > 60)
					{
						$size = 60;
					}
					return "\$form->{$inputField}(\$model, '{$column->name}', array( 'maxlength' => {$maxLength}, 'class' => ''))";
				}
			}
		}
	}

	public function getFieldType($modelClass,$column)
	{
		if($column->type==='boolean')
			return "radiolist";
		else if(stripos($column->dbType,'text')!==false)
			return "textarea";
		else
		{
			if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='password';
			elseif($this->checkExists($column->name, $this->image_fields) || $this->checkExists($column->name, $this->file_fields))
				$inputField='ext.FileFieldWidget';
			elseif(preg_match('/date/i',$column->name))
				$inputField='zii.widgets.jui.CJuiDatePicker';
			else
				$inputField='text';

			return $inputField;
		}
	}
}
