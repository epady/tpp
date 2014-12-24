<?php

class DefaultController extends ApiController
{

	public $allow = array('index','error');	


	public function actionIndex()
	{
		$this->render('index');
	}



	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$data['error'] = Yii::app()->errorHandler->error;
		unset($data['error']['file']);
		unset($data['error']['trace']);
		unset($data['error']['traces']);
		$this->_sendResponse(500,CJSON::encode($data));
		
	}

}