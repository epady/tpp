<?php

class UsecategoryController extends ApiController
{

	public $allow = array('index');



	/**
	 * 获取地区列表
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$data = array();

		$models = UseCategory::model()->findAll();
		foreach($models as $item)
		{
			$data[] = array('id'=>$item->id,'name' =>$item->name);
		}
		$viewData['lists'] = $data;

		$this->_sendResponse(200,$viewData);
	}


}