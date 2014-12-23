<?php

/**
 * Author: UUTAN(uutan@qq.com)
 * 
 * 后台生成的控制器
 * 
 * - $this: the CrudCode object
 * - $time: 2014-09-23 05:44:37
 *
 */

class MemberMoneyController extends Controller
{

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public $pageTitle = "资金管理";


    /**
     * 后台验证码
     * 
     * @return [type] [description]
     */
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x000000,
                'minLength' => 4, //最短为4位
                'maxLength' => 4, //是长为4位

            ),
        );
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
	 * 添加数据
	 * 
	 * @return [type] [description]
	 */
	public function actionCreate()
	{
	    $model = new PayForm;
	    $model->admin_id = Yii::app()->user->id;
	    
		if(isset($_POST['ajax']) && $_POST['ajax']==='edit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
	    if(isset($_POST['PayForm']))
	    { 
	    	$model->attributes = $_POST['PayForm'];

	        if($model->validate() && $model->save())
	        {
	        	Yii::app()->user->setFlash('success', '充值成功。');
	        	unset($_POST);
				$this->redirect(array('index'));
			}
	    }
	    $data['model'] = $model;

		$this->render('create',$data);
	}



	/**
	 * 数据列表管理
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$model=new MemberMoney('search');
		$model->unsetAttributes();

		$user = Administrator::model()->findByPk(Yii::app()->user->id);
		if( $user->role == 'pay' ){
			$model->admin_id = Yii::app()->user->id;
		}
		
		if(isset($_GET['MemberMoney']))
			$model->attributes=$_GET['MemberMoney'];

		$this->render('index',array(
			'model'=>$model,
		));
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
				$this->_model=MemberMoney::model()->findbyPk($_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='member-money-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}

