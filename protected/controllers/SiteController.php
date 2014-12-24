<?php

class SiteController extends Controller
{

	public $allow = array('index');

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
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
		$this->_sendResponse(500,CJSON::encode($data));
		
	}


}
