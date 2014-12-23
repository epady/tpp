<?php

class CacheThumbImageWidget extends CWidget
{

    public $height;
    public $width;
    public $path = '';
    public $alt = '';
    public $class = '';
    public $fullimage = false;
    public $zoom = false;
    public $lazyload = false;
    public $placeholder = '';
    public $id;
    //是否被裁剪
    private $croped = false;
    private $baseurl = '';

    public function run()
    {
        $this->baseurl = Yii::app()->baseUrl;

        $thumb_w = $this->width;
        $thumb_h = $this->height;
        $filename = realpath(Yii::app()->basePath . '/..') . $this->path;
        
        
        $file_exists = true;
        if (!file_exists($filename) || !is_file($filename))
        {
            $file_exists = false;
        }


        if (!$file_exists)
            $src = ($this->width >= 200 ? Yii::app()->theme->baseUrl.'/images/default.gif' : Yii::app()->theme->baseUrl.'/images/default.gif');

        if ($file_exists)
        {
            $pathinfo = pathinfo($filename);
            if (!isset($pathinfo['extension']) || !in_array(strtolower($pathinfo['extension']), array('jpg', 'jpeg', 'gif', 'png')))
            {
                $src = Yii::app()->theme->baseUrl.'/images/default.gif';
            } else
            {
                //根据宽度自动计算高度
                if ($this->width > 0 && $this->height == null)
                {
                    $image = ImageHelper::createFromFile($filename, $pathinfo['extension']);
                    if ($image != false)
                    {
                        $w = imagesx($image->_handle);
                        $h = imagesy($image->_handle);

                        $this->height = intval($this->width * $h / $w);
                    }
                    else
                        $this->height = $this->width;

                    $image = null;
                }

                $thumbname = md5($filename . $this->width . $this->height . filemtime($filename));

                //得到子目录，二级字母数字散列
                $dir_1 = substr($thumbname, 0, 1);
                $dir_2 = substr($thumbname, 1, 1);
                $dir = Yii::app()->basePath . '/../upload/thumb/' . $dir_1 . '/' . $dir_2 . '/';
                if (!file_exists($dir))
                    FileHelper::mkdirs($dir);

                $tofilename = '/upload/thumb/' . $dir_1 . '/' . $dir_2 . '/' . $thumbname . '.' . $pathinfo['extension'];
                $src = $this->baseurl . $tofilename;

                $thumb_exists = true;
                if (!file_exists(Yii::app()->basePath . '/..' . $tofilename) || !is_file(Yii::app()->basePath . '/..' . $tofilename))
                {
                    $thumb_exists = false;
                }


                if (!$thumb_exists)
                {
                    $image = ImageHelper::createFromFile($filename, $pathinfo['extension']);
                    if ($image)
                    {
                        $w = imagesx($image->_handle);
                        $h = imagesy($image->_handle);
                        if ($w > $this->width || $h > $this->height)
                        {
                            $image->crop($this->width, $this->height, array(
                                'fullimage' => $this->fullimage,
                                'pos' => 'center',
                                'bgcolor' => '#ffffff',
                                'transparent' => true,
                            ));
                            $image->save(Yii::app()->basePath . '/..' . $tofilename, 100);
                            $thumb_w = $this->width;
                            $thumb_h = $this->height;
                            $this->croped = true;
                        } else
                        {
                            $src = $this->baseurl . $this->path;
                            $thumb_w = $w;
                            $thumb_h = $h;
                        }
                    } else
                    {
                        $src = Yii::app()->theme->baseUrl.'/images/default.gif';
                        Yii::log('The image file "' . realpath($filename) . '" can not be handled!', 'error');
                    }
                } else
                {
                    $thumb_w = $this->width;
                    $thumb_h = $this->height;
                    $this->croped = true;
                }
            }
        }

        if ($this->lazyload)
        {
            $img = '<img data-original= "' . $src . '" src="' . Yii::app()->baseUrl . '/images/blank.gif" class="' . $this->class . ' lazy" alt="' . $this->alt . '" width="' . $thumb_w . '" height="' . $thumb_h . '" id="' . $this->id . '" />';
        } else
        {
            $img = '<img src= "' . $src . '" class="' . $this->class . '" alt="' . $this->alt . '" width="' . $thumb_w . '" height="' . $thumb_h . '" id="' . $this->id . '" />';
        }
        
        echo $img;
        
    }

}
