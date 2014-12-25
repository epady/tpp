<?php

class CasesController extends ApiController
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
        
        $item = Cases::model()->findAll($criteria);

        return $item;
    }


	/**
	 * 列表
	 * 
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
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
			$var['title'] = $item->title;
			$var['shop_id'] = $item->shop_id;
			$var['shop_name'] = $item->shop->name;
			$var['city_id'] = $item->city_id;
			$var['city_name'] = $item->city_id ? $item->area->name : '全国';

			$var['image'] = Yii::app()->request->getBaseUrl(true).$item->image;
			$var['created'] = date('Y-m-d',$item->created);

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
		$var['title'] = $models->title;
		$var['shop_id'] = $models->shop_id;
		$var['shop_name'] = $models->shop->name;
		$var['city_id'] = $models->city_id;
		$var['city_name'] = $models->city_id ? $models->area->name : '全国';

		$var['image'] = Yii::app()->request->getBaseUrl(true).$models->image;
		
		$var['content'] = $models->content;

		$var['created'] = date('Y-m-d',$models->created);

		// 详情图
		$cr = new CDbCriteria;
		$cr->compare('case_id', $models->id);
		$cr->order = 'sort_order DESC,id ASC';

		$photos = CasePhotos::model()->findAll($cr);
		$photoArray = array();
		foreach($photos  as $item)
		{
			$sub = array();
			$sub['id'] = $item->id;
			$sub['image'] = Yii::app()->request->getBaseUrl(true).$item->image;
			$sub['title'] = $item->title;
			$photoArray[] = $sub;
		}
		$var['photos'] = $photoArray;

		$data['cases'] = $var;

		$this->_sendResponse(200, $data);
	}


}