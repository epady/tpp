<?php
/**
 * 
 * 后台所有控制器的基类
 * 
 * 所有权限基于 RController 类
 * 
 * 
 */
class Controller extends RController
{

	/**
	 * @return 过滤器
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
    
	/**
	 * @var 使用模板
	 */
	public $layout='application.admin.views.layouts.main';

	/**
	 * @var 共用菜单
	 */
	public $menu=array();

	/**
	 * @var 面包屑
	 */
	public $breadcrumbs=array();

    
    /**
     * 设置上传目录自动生成
     * 
     * @param  [type] $modelname [description]
     * @return [type]            [description]
     */
    public function getUploadDir($modelname)
    {
        $updir = Yii::app()->basePath . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'upload' .DIRECTORY_SEPARATOR . $modelname .DIRECTORY_SEPARATOR ;
        if(!file_exists($updir)) FileHelper::mkdirs($updir);
        return $updir;
    }
    
    /**
     * 生成绝对地址
     * 
     * @param  [type] $modelname [description]
     * @return [type]            [description]
     */
	public function getUploadBase($modelname)
    {
        $updir = '/upload/' . $modelname . '/';
        return $updir;
    }



    /**
     * 编辑器上传处理动作返回json数据
     * 根据目录，年月为目录来生成
     * 
     * @return [type] [description]
     */
	public function actionUpload()
	{
		header('Content-type: text/html; charset=UTF-8');
		
		//定义允许上传的文件扩展名
		$ext_arr = array(
			'image' => array('gif', 'jpg', 'jpeg', 'png'),
			'flash' => array('swf', 'flv'),
			'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
			'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
		);

		//最大文件大小
		$max_size = 1000000;
		try
		{
			$file = CUploadedFile::getInstanceByName('imgFile');
			
			if($file->size > $max_size)
				throw new CException('文件超过了允许的大小');
			
			if(!in_array(strtolower($file->extensionName), $ext_arr[$_GET['dir']]))
				throw new CException('文件格式不允许');
			
			$dir = '/upload/'.$_GET['dir'].'/'.date('Y-m').'/';
			
			if(!is_dir($truedir = Yii::app()->basePath.'/..'. $dir))
				FileHelper::mkdirs($truedir);

			$filepath = $dir .time() . rand(1000,9999) .'.'. $file->extensionName;
			$filename = Yii::app()->basePath. '/..' . $filepath;
			$file->saveAs($filename);
			
			echo json_encode(array('error' => 0, 'url' => $filepath));
		}
		catch(CException $ex)
		{
			echo json_encode(array('error' => 1, 'message' => $ex->getMessage()));
		}
	}
    

	
	
}