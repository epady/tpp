<?php

/**
 * Author: UUTAN(uutan@qq.com)
 * 
 * 后台生成的控制器
 * 
 * - $this: the CrudCode object
 * - $time: 2014-10-09 04:15:05
 *
 */

class AboutController extends Controller
{

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public $pageTitle = "关于我们管理";


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
	 * 更新数据
	 * 
	 * @return [type] [description]
	 */

	public function actionUpdate()
	{
		$form = new Form('backend.views.about.formConfig');
	    $form->model = $this->loadModel();
	    
		if(isset($_POST['ajax']) && $_POST['ajax']==='edit-form')
		{
			echo CActiveForm::validate($form->model);
			Yii::app()->end();
		}
		
			/**
		$images = array ('');//要上传的文件
		//必须在 submitted 方法之前 unset掉，否则如果不上传新文件，老文件将丢失
		foreach($images as $name) unset ( $_POST [get_class ( $form->model )] [$name] );
			*/
		
		if($form->submitted('btnsubmit'))
	    {
				/**
			$old_images = array();//记录旧文件
			foreach ( $images as $name ) 
			{
				$image = CUploadedFile::getInstance ( $form->model, $name );
				if ($image) {
					$filename = time () . rand ( 1000, 9999 ) . '.' . $image->getExtensionName ();
					
					$dir = '/upload/' . $name . '/' . date ( 'Ym' ) . '/';
					if (! file_exists ( Yii::app ()->basePath . '/..' . $dir ))
						FileHelper::mkdirs ( Yii::app ()->basePath . '/..' . $dir );
					
					$filepath = Yii::app ()->basePath . '/..' . $dir . $filename;
					if ($image->saveAs ( $filepath )) {
						$old_images[] = $form->model->$name;
						$form->model->$name = $dir . $filename;
					}
				}
			}
				*/
			
	    	if($form->validate())
	    	{
		        $model = $form->model; 
		        if($model->save(false))
		        {
				/**
		        	//删除掉旧文件
		        	foreach($old_images as $oldimg)
		        	{
		        		@unlink(Yii::app ()->basePath . '/..' .$oldimg);
		        	}
		        	*/		        	
		        	Yii::app()->user->setFlash('success',About::model()->modelName."“{$model}”已成功修改！");
					$this->redirect(array('view','id'=>$model->id));
				}
	    	}
			/**
	    	else 
	    	{
	    		//表单验证失败，则删除已上传的新文件
	    		foreach ( $images as $name ) 
	    		{
	    			@unlink(Yii::app ()->basePath . '/..' .$form->model->$name);
	    		}
	    	}
			*/
	    }

		$this->render('update',array(
			'form'=>$form,
		));
	}


	/**
	 * 数据列表管理
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$model=new About('search');
		$model->unsetAttributes();
		if(isset($_GET['About']))
			$model->attributes=$_GET['About'];

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
				$this->_model=About::model()->findbyPk($_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='about-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}

