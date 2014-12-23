<?php
Yii::import('zii.widgets.grid.CButtonColumn');

class EButtonColumn extends CButtonColumn
{
	public function init()
	{
// 		$url = Yii::app()->request->baseUrl . '/images/';
// 		$this->viewButtonImageUrl = $url . 'view.png';
// 		$this->updateButtonImageUrl = $url . 'update.png';
// 		$this->deleteButtonImageUrl = $url . 'delete.png';
		$this->viewButtonImageUrl = false;
		$this->updateButtonImageUrl = false;
		$this->deleteButtonImageUrl = false;
		$this->header = '操作';
		$this->headerHtmlOptions=array('style'=>'text-align:center');
		$this->htmlOptions = array('nowrap'=>'nowrap','style'=>'text-align:center');
		parent::init();
	}
}
