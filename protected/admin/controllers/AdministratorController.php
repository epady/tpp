<?php
/**
 * 后台用户管理控制器
 *
 * 
 */
class AdministratorController extends Controller
{

	private $_model;
    public $pageTitle = '后台用户管理';
    
    private $auth;
    
    public function __construct($id)
    {
        parent::__construct($id);
        $this->auth = Yii::app()->authManager;
    }


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
	 * 添加后台用户
	 * 
	 * @return [type] [description]
	 */
	public function actionCreate()
	{
		$model=new Administrator;

		$this->performAjaxValidation($model);

		if(isset($_POST['Administrator']))
		{
			$model->attributes=$_POST['Administrator'];
            $model->salt = rand(10000, 99999);
            $model->password = md5(md5($model->password).$model->salt);
            $model->created = time();
			if($model->save())
            {
            	Yii::app()->authManager->assign($model->role, $model->id);
                Yii::app()->user->setFlash('success',"管理员“{$model->username}”已成功添加！");
				$this->redirect(array('index'));
            }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	
	/**
	 * 更新后台用户
	 * 
	 * @return [type] [description]
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();

		$this->performAjaxValidation($model);

		if(isset($_POST['Administrator']))
		{
            if(!empty($_POST['Administrator']['password']))
                $_POST['Administrator']['password'] = md5(md5($_POST['Administrator']['password']).$model->salt);
            else
                unset($_POST['Administrator']['password']);
            
			$model->attributes=$_POST['Administrator'];
			if($model->save())
            {
                Yii::app()->user->setFlash('success',"管理员“{$model->username}”已成功修改！");
				$this->redirect(array('index'));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * 删除后台用户
	 * 
	 * @return [type] [description]
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$this->loadModel()->delete();
			if(!isset($_GET['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}


	/**
	 * 后台用户管理
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$model=new Administrator('search');
		if(isset($_GET['Administrator']))
			$model->attributes=$_GET['Administrator'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Administrator::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'您要浏览的页面不存在，可能是已被删除或者URL地址错误。');
		}
		return $this->_model;
	}


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='administrator-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
