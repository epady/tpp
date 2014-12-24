<?php

class BaseController extends ApiController
{

	public $allow = array('index','view');



	/**
	 * 基地列表
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$data = array();

		$models = Base::model()->findAll();

		$baseArray = array();
		foreach($models as $item)
		{
			$baseArray[] = $item->attributes;
		}

		$data['bases'] = $baseArray;

		$this->_sendResponse(200, $data);
	}


	/**
	 * 看查基地详情
	 *
	 * 
	 * @return [type] [description]
	 */
	public function actionView($id)
	{
			
		$data = array();

		$bases = Base::model()->findByPk($id);

		if( $bases === null )
		{
			$data['message'] = '该ID数据不存在';

			$this->_sendResponse(404,$data);
		}

		$data['base'] = $bases->attributes;


		$this->_sendResponse(200, $data);


	}

}