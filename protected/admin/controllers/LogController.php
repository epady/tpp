<?php

/**
 * Author: UUTAN(uutan@qq.com)
 * 
 * 后台生成的控制器
 * 
 * - $this: the CrudCode object
 * - $time: 2014-09-26 08:43:41
 *
 */

class LogController extends Controller
{

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public $pageTitle = "日志管理";


    /**
     * 查看单条记录
     * 
     * @return [type] [description]
     */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * 数据列表管理
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$model=new Log('search');
		$model->unsetAttributes();
		if(isset($_GET['Log']))
			$model->attributes=$_GET['Log'];


		$this->render('index',array(
			'model'=>$model,
		));
	}
    

	/**
	 * 多元条件数据扩展操作
	 * 
	 * @return [type] [description]
	 */    
	public function actionOperate()
	{
		if(Yii::app()->request->isAjaxRequest && !empty($_POST['id']))
		{
			if($_POST['operation'] == 'delete')
			{
				$objs = Log::model()->findAllByPk($_POST['id']);
				if($objs)
				{
					foreach($objs as $obj)
					{
						$obj->delete();
					}
				}
			}
			echo '操作成功！';
		}
	}


	/**
	 * 接收指定数据
	 * 
	 * @return [type] [description]
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Log::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'您要浏览的页面不存在，可能是已被删除或者URL地址错误。');
		}
		return $this->_model;
	}


	/**
	 * 添加/更新时验证数据
	 * 
	 * @return [type] [description]
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='log-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}

