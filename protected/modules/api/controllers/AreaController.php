<?php

class AreaController extends ApiController
{

	public $allow = array('index','get');


	/**
	 * 根据经纬度得知城市
	 * 
	 * @return [type] [description]
	 */
	public function actionGet()
	{
		
	}



	/**
	 * 获取地区列表
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$data = array();

		$models = Area::model()->findAll();
		foreach($models as $item)
		{
			$data[] = array('id'=>$item->id,'name' =>$item->name);
		}
		$viewData['lists'] = $data;

		$this->_sendResponse(200,$viewData);
	}


}