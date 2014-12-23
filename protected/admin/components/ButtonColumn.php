<?php
Yii::import('zii.widgets.grid.CButtonColumn');
class ButtonColumn extends CButtonColumn
{
	public $viewButtonImageUrl = false;
	public $updateButtonImageUrl = false;
	public $updateButtonLabel = '修改';
	public $deleteButtonImageUrl = false;
	public $viewButtonOptions = array('target'=>'_blank');
	public $htmlOptions = array('style'=>'text-align:center;width:150px;');
	public $headerHtmlOptions = array('style'=>'text-align:center;width:150px;');
	public $header = '操作';
}