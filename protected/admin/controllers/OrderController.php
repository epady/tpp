<?php

/**
 * Author: UUTAN(uutan@qq.com)
 * 
 * 后台生成的控制器
 * 
 * - $this: the CrudCode object
 * - $time: 2014-09-17 05:41:42
 *
 */

class OrderController extends Controller
{

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public $pageTitle = "订单管理";


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
	 * 获取处理订单
	 * 
	 * @return [type] [description]
	 */
	public function actionGet()
	{
		$user = Administrator::model()->findByPk(Yii::app()->user->id);
		if( $user->order_status )
		{
			$this->redirect(array('/order/update','id'=>$user->order_id));
		}

		// 按顺序分配订单
		$cr = new CDbCriteria;
		$cr->compare('is_ass',0);
		$cr->addInCondition('status',array('0','3'));
		$cr->order = 'id ASC';
		$model = Order::model()->find($cr);
		if( $model )
		{
			Order::model()->updateByPk($model->id, array('is_ass'=>1,'status'=>1));
			Administrator::model()->updateByPk(Yii::app()->user->id, array('order_id'=>$model->id,'order_status'=>1));
			
			$this->redirect(array('/order/update','id'=>$model->id));
		}else{
			Yii::app()->user->setFlash('error','暂无新订单，请耐心等待');
			$this->redirect(array('site/welcome'));
		}

	}




	/**
	 * 更新数据
	 * 
	 * @return [type] [description]
	 */

	public function actionUpdate($id)
	{
		$user = Administrator::model()->findByPk(Yii::app()->user->id);

		if($user->order_id != $id || !$user->order_status)
		{
			Yii::app()->user->setFlash('error','您没有权限处理该订单');
			$this->redirect(array('/site/welcome'));
		}

		$model = Order::model()->findByPk($id);
	    
		if(isset($_POST['ajax']) && $_POST['ajax']==='edit-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$model->status = 4;
		

		if( isset($_POST['Order']) )
		{
			$model->attributes = $_POST['Order'];
			
			if( $model->status == 2 )
			{
				$model->is_ass = 0;
			}

			// 上传图片
			$images = array ('reimage');
			foreach ( $images as $name ) 
			{
				$image = CUploadedFile::getInstance ( $model, $name );
				if ($image) {
					$filename = time () . rand ( 1000, 9999 ) . '.' . $image->getExtensionName ();
					
					$dir = '/upload/' . $name . '/' . date ( 'Ym' ) . '/';
					if (! file_exists ( Yii::app ()->basePath . '/..' . $dir ))
						FileHelper::mkdirs ( Yii::app ()->basePath . '/..' . $dir );
					
					$filepath = Yii::app ()->basePath . '/..' . $dir . $filename;
					if ($image->saveAs ( $filepath )) 
					{
						$model->$name = $dir . $filename;
					}
				}
				unset ( $_POST [get_class ( $model )] [$name] );
			}			

			if( $model->save() )
			{

				Administrator::model()->updateByPk(Yii::app()->user->id, array('order_id'=>0,'order_status'=>0));

				Yii::app()->user->setFlash('success','订单处理完成.');
				$this->redirect(array('site/welcome'));
			}
		}


		$data['model'] = $model;

		$this->render('update',$data);
	}



	/**
	 * 数据列表管理
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$model=new Order('search');
		$model->unsetAttributes();
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
    

	/**
	 * 取消订单
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionChannel()
	{
		$model = $this->loadModel();

		if( $model->status != '0' )
		{
			throw new CHttpException(400, '该订单状态为：'.$model->statusStr.',不能取消订单');
		}

		// 更新状态
		Order::model()->updateByPk($model->id, array('status'=>6));

		// 退款
		$mm = new MemberMoney;
		$mm->member_id = $model->member_id;
		$mm->inmoney = Yii::app()->config->get('order_price');
		$mm->admin_id = '0';
		$mm->content = '订单号：'.$model->id.'，被取消退款';
		if($mm->save())
		{
			Yii::app()->user->setFlash('success','订单取消成功，退回订单款项');
			$this->redirect(array('index'));
		}

		
	}


	/**
	 * 上传图片
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionUpload($id)
	{

		$model = $this->loadModel();

		$image = CUploadedFile::getInstanceByName ('Filedata');
		if ($image) {
			$filename = time () . rand ( 1000, 9999 ) . '.' . $image->getExtensionName ();
			
			$dir = '/upload/reimage/' . date ( 'Ym' ) . '/';
			if (! file_exists ( Yii::app ()->basePath . '/..' . $dir ))
				FileHelper::mkdirs ( Yii::app ()->basePath . '/..' . $dir );
			
			$filepath = Yii::app ()->basePath . '/..' . $dir . $filename;
			if ($image->saveAs ( $filepath )) 
			{
				$img = $dir.$filename;
		        $ret['result_code'] = 1;
		        $ret['result_des'] = $img;

		        Order::model()->updateByPk($model->id, array('reimage'=>$img));

		        echo CJSON::encode($ret);
			}
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
				$this->_model=Order::model()->findbyPk($_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}

