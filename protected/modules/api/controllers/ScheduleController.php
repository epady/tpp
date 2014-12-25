<?php

/**
 * 摄影师档期
 * 
 */
class ScheduleController extends ApiController
{

	public $allow = array('index','view');

	public function actionIndex()
	{
		$this->render('index');
	}


}