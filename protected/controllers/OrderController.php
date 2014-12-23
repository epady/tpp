<?php

class OrderController extends Controller
{


	/**
	 * 上传图片并生成订单
	 * 
	 * @return [type] [description]
	 */
	public function actionCreate()
	{
		$member = $this->member;
		$order_price = Yii::app()->config->get('order_price');
		
		$post = CJSON::decode(file_get_contents('php://input'));
		
		// 判断余额是否足
		if( $member['balance'] < $order_price )
		{
			$data['message'] = '您的余额不足';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 判断是否上传图片
		// $image = CUploadedFile::getInstanceByName ('image');
		// if ($image === null) {
		// 	$data['message'] = '请选择上传照片';
		// 	$this->_sendResponse(400,CJSON::encode($data));
		// }
		
		// $filename = time () . rand ( 1000, 9999 ) . '.' . $image->getExtensionName ();
		
		// $dir = '/upload/' . date ( 'Ym' ) . '/';
		// if (! file_exists ( Yii::app ()->basePath . '/..' . $dir ))
		// 	FileHelper::mkdirs ( Yii::app ()->basePath . '/..' . $dir );
		
		// $filepath = Yii::app ()->basePath . '/..' . $dir . $filename;
		// if ($image->saveAs ( $filepath )) 
		
		$image = isset($post['image']) ? $post['image'] : '';
		$service = isset($post['service']) ? $post['service'] : '';
		$gw = isset($post['gw']) ? $post['gw'] : '';

		$dir = '/upload/'.date('Ym').'/';
		$filename = $filename = time () . rand ( 1000, 9999 ) . '.jpg';
		
		if (! file_exists ( Yii::app ()->basePath . '/..' . $dir ))
		{
			FileHelper::mkdirs ( Yii::app ()->basePath . '/..' . $dir );
		}

		$filepath = Yii::app ()->basePath . '/..' . $dir . $filename;

		if( $image )
		{
			$image = substr($image, 22); // 去掉前面的转码
			$image = base64_decode($image); // 64位转码
			FileHelper::file_force_contents($filepath, $image);
			$dbImage = $dir . $filename;

			// 生成订单
			$model = new Order;
			$model->member_id = $member['id'];
			$model->image = $dbImage;
			$model->service = $service;
			$model->order_price = $order_price;
			$model->gw = $gw;
			if(!$model->save())
			{
				$data['message'] = $model->getErrors();
				$this->_sendResponse(400,CJSON::encode($data));
			}

			// 扣费
			$money = new MemberMoney;
			$money->member_id = $member['id'];
			$money->outmoney = $order_price;
			$money->content = '生成订单消费 '.number_format($order_price,2).'元。订单ID:'.$model->id;
			$money->save();

			$data['member'] = $member;
			$data['member']['order_id'] = $model->id;
			$data['member']['order_status'] = $model->status;
			
			// show
			$data['order'] = $model->attributes;

			$data['message'] = '上传成功，'.$model->message;

			$this->_sendResponse(200,CJSON::encode($data));

		}else{
			$data['message'] = '图片上传失败';
			$this->_sendResponse(400,CJSON::encode($data));
		}
		
	}


	/**
	 * 修改图片和相关资料
	 * 
	 * @return [type] [description]
	 */
	public function actionUpdate()
	{
		$member = $this->member;

		$post = CJSON::decode(file_get_contents('php://input'));
		
		$id = isset($post['id']) ? intval(trim($post['id'])) : 0;

		// 判断是否有权限编辑
		$model = Order::model()->findByPk($id, 'member_id = :member_id', array(':member_id'=>$member['id']));
		if( $model === null )
		{
			$data['message'] = '该订单不存在，或您还没权限编辑';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 旧图
		$oldImage = $model->image;

		$image = isset($post['image']) ? $post['image'] : '';

		$dir = '/upload/'.date('Ym').'/';
		$filename = $filename = time () . rand ( 1000, 9999 ) . '.jpg';
		
		if (! file_exists ( Yii::app ()->basePath . '/..' . $dir ))
		{
			FileHelper::mkdirs ( Yii::app ()->basePath . '/..' . $dir );
		}

		$filepath = Yii::app ()->basePath . '/..' . $dir . $filename;

		if( $image )
		{
			$image = substr($image, 22); // 去掉前面的转码
			$image = base64_decode($image); // 64位转码
			FileHelper::file_force_contents($filepath, $image);
			$dbImage = $dir . $filename;

			// 编辑订单
			$model->image = $dbImage;
			$model->status = 3; // 您已修改并提交，等待预录人员处理
			if(!$model->save())
			{
				$data['message'] = $model->getErrors();
				$this->_sendResponse(400,CJSON::encode($data));
			}

			// 删除旧图
			@unlink(Yii::app()->basePath.'/..'.$oldImage);

			// show
			$data['order'] = $model->attributes;

			$data['member'] = $member;
			$data['member']['order_id'] = $model->id;
			$data['member']['order_status'] = $model->status;

			$this->_sendResponse(200,CJSON::encode($data));

		}else{
			$data['message'] = '图片上传失败';
			$this->_sendResponse(400,CJSON::encode($data));
		}
	}


	/**
	 * 确认订单无异议
	 * 
	 * @return [type] [description]
	 */
	public function actionConfirm()
	{
		$member = $this->member;
		$post = CJSON::decode(file_get_contents('php://input'));

		$id = isset($post['id']) ? intval(trim($post['id'])) : 0;
		$diff = isset($post['diff']) ? intval(trim($post['diff'])) : 0;
		$content = isset($post['content']) ? trim($post['content']) : '';


		$model = Order::model()->findByPk($id, 'member_id = :member_id', array(':member_id'=>$member['id']));
		if( $model === null )
		{
			$data['message'] = '该订单不存在，或您还没权限编辑';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		if( $model->status != 4 )
		{
			$data['message'] = '该订单并没有完成，无法提交异议意见';
			$this->_sendResponse(400,CJSON::encode($data));
 		}

 		$orderData['status'] = $diff ? 3 : 5;
 		$orderData['content'] = $content;
 		$orderData['is_diff'] = $diff;
 		if( $diff )
 		{
 			$orderData['is_ass'] = 0;
 		}

		$data['member'] = $member;

 		// 更新状态 并结束订单
 		Order::model()->updateByPk($id, $orderData);
 		if( !$diff )
 		{
			Member::model()->updateByPk($member['id'], array('order_id'=>0, 'order_status'=>0));	
			$data['member']['order_id'] = 0;
			$data['member']['order_status'] = 0;
		}

 		$data['message'] = '提交完成';
 		$this->_sendResponse(200,CJSON::encode($data));
	}


	/**
	 * 查询订单
	 * 
	 * @return [type] [description]
	 */
	public function actionFind()
	{
		$member = $this->member;
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$model = Order::model()->findByPk($id);

		if( $model === null )
		{
			$data['message'] = '暂无处理订单';
			$this->_sendResponse(200, CJSON::encode($data));
		}

		if( $model->member_id != $member['id'] )
		{
			$data['message'] = '该订单没有权限访问';
			$this->_sendResponse(200,CJSON::encode($data));
		}

		$data['order'] = array(
			'id' => $model->id,
			'sn' => $model->sn,
			'status' => $model->statusStr,
			'image' => $model->imageStr,
			'reimage' => $model->reimageStr,
			'message' => $model->message,
			'order_price' => $model->order_price,
			'gw' => $model->gw,
			'service' => $model->service,

			'receipt' => $model->receipt,
			'restart' => $model->status == 2 ? true : false, // 订单审核是否通过，是否重新上传
			'ischeck' => $model->status == 4 ? true : false, // 订单是否有异议
			'created' => date('Y-m-d H:i:s',$model->created),
			'updated' => date('Y-m-d H:i:s',$model->updated),
		);
		$data['message'] = $model->message;

		$this->_sendResponse(200, CJSON::encode($data));

	}


	/**
	 * 取消订单
	 * 
	 * @return [type] [description]
	 */
	public function actionChannel()
	{
		$member = $this->member;
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$model = Order::model()->findByPk($id);

		if( $model === null )
		{
			$data['message'] = '暂无处理订单';
			$this->_sendResponse(400, CJSON::encode($data));
		}

		if( $model->member_id != $member['id'] )
		{
			$data['message'] = '该订单没有权限访问';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		if( $model->status != '0' )
		{
			$data['message'] = '该订单目前状态为：'.$model->statusStr.'，不能取消订单';
			$this->_sendResponse(400,CJSON::encode($data));
		}

		// 更新状态
		Order::model()->updateByPk($id, array('status'=>6));

		// 退款
		$mm = new MemberMoney;
		$mm->member_id = $member['id'];
		$mm->inmoney = Yii::app()->config->get('order_price');
		$mm->admin_id = '0';
		$mm->content = '订单号：'.$model->id.'，被取消退款';
		if($mm->save())
		{
			$data['message'] = '订单取消成功，退回订单款项';
			$this->_sendResponse(200, CJSON::encode($data));
		}
		
	}


	/**
	 * 订单列表
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$pageSize = 10;

		$member = $this->member;
		$cr = new CDbCriteria;
		$cr->compare('member_id',$member['id']);

		$cr->limit = $pageSize;
		
		$cr->order = 'id DESC';


		$models = Order::model()->findAll($cr);

		$order = array();
		foreach($models as $item)
		{
			$order[] = array(
				'id' => $item->id,
				'sn' => $item->id,
				'status' => $item->statusStr,
				'order_price' => number_format($item->order_price,2),
				'created' => date('Y-m-d H:i:s', $item->created),
				'updated' => date('Y-m-d H:i:s', $item->updated),
			);
		}
		$data['list'] = $order;

		$this->_sendResponse(200, CJSON::encode($data));

	}

}