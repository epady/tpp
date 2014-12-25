<?php

class ServiceController extends ApiController
{

	public $allow = array('index','view');


	/**
	 * 分页获取项目
	 * 
	 * @param  integer $page [description]
	 * @return [type]        [description]
	 */
    protected function getListData($page=0,$city_id=0, $shop_id=0)
    {
        $criteria = new CDbCriteria();
        if( $shop_id )
        {
        	$criteria->compare('shop_id', $shop_id);
        }
        if( $city_id != 0 )
        {
        	$city_ids[] = $city_id;
        	$areas = Area::options($city_id);
        	foreach($areas as $key => $val)
        	{
        		$city_ids[] = $key;
        	}
        	$criteria->addInCondition('city_id',$city_ids);
        }else{
        	$criteria->compare('city_id', $city_id);
        }
        $criteria->order = 'id DESC';
        
        $criteria->limit = $this->pageSize;
        $criteria->offset = ($page - 1) * $this->pageSize;
        
        $item = Service::model()->findAll($criteria);

        return $item;
    }


	/**
	 * 列表
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$city_id = isset($_GET['city_id']) ? intval($_GET['city_id']) : 0;
		$shop_id = isset($_GET['shop_id']) ? intval($_GET['shop_id']) : 0;
		
		$data['page'] = $page;
		$data['city_id'] = $city_id;
		$data['shop_id'] = $shop_id;

		$listData = $this->getListData($page,$city_id, $shop_id);

		$subData = array();

		// TODO  图片生成指定大小后添加全相对路径
		foreach($listData as $item)
		{
			$var = array();
			$var['id'] = $item->id;
			$var['name'] = $item->name;
			$var['shop_id'] = $item->shop_id;
			$var['shop_name'] = $item->shop->name;
			$var['city_id'] = $item->city_id;
			$var['city_name'] = $item->city_id ? $item->area->name : '全国';

			$var['image'] = Yii::app()->request->getBaseUrl(true).$item->image;
			$var['price'] = $item->price;
			$var['tags'] = $item->tags;
			$var['awaytime'] = $item->awaytime;
			$var['photo_count'] = $item->photo_count;
			$var['base_count'] = $item->base_count;
			$var['dipian'] = $item->dipian;

			$var['created'] = date('Y-m-d',$item->created);
			$var['updated'] = date('Y-m-d',$item->updated);

			$subData[] = $var;
		}

		$data['lists'] = $subData;

		// 判断是否有下一页
		$data['nextpage'] = false;
		$nextProject  = $this->getListData($page+1,$city_id, $shop_id);
		if( count($nextProject) )
		{
			$data['nextpage'] = true;
		}

		$this->_sendResponse(200, $data);
	}



	/**
	 * 详情页
	 * 
	 * @return [type] [description]
	 */
	public function actionView($id)
	{
		$data = array();

		$models = Cases::model()->findByPk($id);

		if( $models === null )
		{
			$data['message'] = '该数据不存在';
			$this->_sendResponse(404,$data);
		}

		$var = array();
		$var['id'] = $models->id;
		$var['name'] = $models->name;
		$var['shop_id'] = $models->shop_id;
		$var['shop_name'] = $models->shop->name;
		$var['city_id'] = $models->city_id;
		$var['city_name'] = $models->city_id ? $models->area->name : '全国';

		$var['image'] = Yii::app()->request->getBaseUrl(true).$item->image;
		$var['price'] = $item->price;
		$var['tags'] = $item->tags;
		$var['awaytime'] = $item->awaytime;
		$var['photo_count'] = $item->photo_count;
		$var['base_count'] = $item->base_count;
		$var['dipian'] = $item->dipian;

		$var['created'] = date('Y-m-d',$item->created);
		$var['updated'] = date('Y-m-d',$item->updated);


		$data['service'] = $var;

		$this->_sendResponse(200, $data);
	}


}