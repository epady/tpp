<?php
/**
 * @version $Id: CTImageWidget.php 105 2014-06-26 09:18:40Z lonestone $
 */

class CTImageWidget extends CWidget
{
	public $height;
	public $width = "100";
	public $path = '';
	public $alt = '';
	public $class = '';
	public $fullimage = false;
	
    public function run()
	{
//		if(!isset($this->path)){
//			throw new CHttpException(500,'"path" have to be set!');
//		}
		
	    $filename = Yii::app()->basePath .'/..'. $this->path;
        if(!file_exists($filename) || !is_file($filename)) $src = '/images/nopic_s.gif' ;

        if(is_file($filename))
        {
            $pathinfo = pathinfo($filename);
            if(!isset($pathinfo['extension']))
            {
            	$src = Yii::app()->baseUrl.'/images/nopic.gif';
            }
            else
            {
	            $thumbname = md5($filename.$this->width.$this->height);
	            
	            //得到子目录，二级字母数字散列
	            $dir_1 = substr($thumbname,0,1);
	            $dir_2 = substr($thumbname,1,1);
	            $dir = Yii::app()->basePath.'/../upload/thumb/'.$dir_1.'/'.$dir_2.'/';
	            if(!file_exists($dir)) FileHelper::mkdirs($dir);
	            
	            $tofilename = '/upload/thumb/'.$dir_1.'/'.$dir_2.'/'.$thumbname.'.'.$pathinfo['extension'];
	            $src = Yii::app()->baseUrl.$tofilename;

	            //根据宽度自动计算高度
                if($this->width > 0 && $this->height == null)
                {
                    $image = ImageHelper::createFromFile($filename, $pathinfo['extension']);
                    if($image != false)
                    {
                        $w = imagesx($image->_handle);
                        $h = imagesy($image->_handle);
                        
                        $this->height = intval($this->width * $h / $w);
                    }
                    else 
                        $this->height = $this->width;
                }

	            if(!is_file(Yii::app()->basePath.'/..'. $tofilename))
	            {
	                $image = ImageHelper::createFromFile($filename, $pathinfo['extension']);
	                if($image != false)
	                {
	                    $image->crop($this->width, $this->height, array(
	                        'fullimage' => $this->fullimage,
	                        'pos' => 'center',
	                        'bgcolor' => '#ffffff',
	                    	'transparent'=>true,
	                    ));
	                    $image->save(Yii::app()->basePath.'/..'. $tofilename, 100);
	                }
	                else
	                {
	                    $src = Yii::app()->baseUrl.'/images/nopic.gif';
	                    Yii::log('The image file "'.realpath($filename).'" can not be handled!', 'error'); 
	                }
	            }
            }
        }
        
        echo '<img src= "'.$src.'" class="'.$this->class.'" alt="'.$this->alt.'" height="'.$this->height.'" width="'.$this->width.'" />';
	}
}
