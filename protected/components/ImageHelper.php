<?php
/**
 * 定义 ImageHelper 类和 ImageGD 类
 *
 * @link http://qeephp.com/
 * @copyright Copyright (c) 2006-2009 Qeeyuan Inc. {@link http://www.qeeyuan.com}
 * @license New BSD License {@link http://qeephp.com/license/}
 * @version $Id: ImageHelper.php 3558 2012-08-22 04:23:57Z lonestone $
 * @package helper
 */

/**
 * ImageHelper 类封装了针对图像的操作
 *
 * 开发者不能直接构造该类的实例，而是应该用 ImageHelper::createFromFile()
 * 静态方法创建一个 Image 类的实例。
 *
 * 操作大图片时，请确保 php 能够分配足够的内存。
 *
 * @author YuLei Liao <liaoyulei@qeeyuan.com>
 * @version $Id: ImageHelper.php 3558 2012-08-22 04:23:57Z lonestone $
 * @package helper
 */
abstract class ImageHelper
{
    /**
     * 从指定文件创建 ImageGD 对象
     *
     * 用法：
     * @code php
     * $image = ImageHelper::createFromFile('1.jpg');
     * $image->resize($width, $height);
     * $image->saveAsJpeg('2.jpg');
     * @endcode
     *
     * 对于上传的文件，由于其临时文件名中并没有包含扩展名。
     * 因此需要采用下面的方法创建 Image 对象：
     *
     * @code php
     * $ext = pathinfo($_FILES['postfile']['name'], PATHINFO_EXTENSION);
     * $image = Image::createFromFile($_FILES['postfile']['tmp_name'], $ext);
     * @endcode
     *
     * @param string $filename 图像文件的完整路径
     * @param string $fileext 指定扩展名
     *
     * @return ImageGD 从文件创建的 ImageGD 对象
     * @throw Q_NotImplementedException
     */
    static function createFromFile($filename, $fileext = '', $return_handle = false)
    {
    	if($fileext =='') $fileext = pathinfo ( $filename, PATHINFO_EXTENSION );
        $fileext = trim(strtolower($fileext), '.');
        if(empty($fileext)) $fileext = 'jpg';
        $ext2functions = array(
            'jpg'  => 'imagecreatefromjpeg',
            'jpeg' => 'imagecreatefromjpeg',
            'png'  => 'imagecreatefrompng',
            'gif'  => 'imagecreatefromgif',
        	'bmp'  => 'imagecreatefromwbmp'
        );

        if (!isset($ext2functions[$fileext]))
        {
        	throw new Exception('Not Implemented: imagecreateform' . $fileext);
        }
        
        $handle = @call_user_func($ext2functions[$fileext], $filename);
        if($return_handle) return $handle;
        if(is_resource($handle)) 
            return new ImageGD($handle);
        else 
            return false;
    }

	/**
	 * 将 16 进制颜色值转换为 rgb 值
     *
     * 用法：
     * @code php
     * $color = '#369';
     * list($r, $g, $b) = ImageHelper::hex2rgb($color);
     * echo "red: {$r}, green: {$g}, blue: {$b}";
     * @endcode
     *
     * @param string $color 颜色值
     * @param string $default 使用无效颜色值时返回的默认颜色
	 *
	 * @return array 由 RGB 三色组成的数组
	 */
	static function hex2rgb($color, $default = 'ffffff')
	{
        $hex = trim($color, '#&Hh');
        $len = strlen($hex);
        if ($len == 3)
        {
            $hex = "{$hex[0]}{$hex[0]}{$hex[1]}{$hex[1]}{$hex[2]}{$hex[2]}";
        }
        elseif ($len < 6)
        {
            $hex = $default;
        }
        $dec = hexdec($hex);
        return array(($dec >> 16) & 0xff, ($dec >> 8) & 0xff, $dec & 0xff);
	}
}

/**
 * ImageGD 类封装了一个 gd 句柄，用于对图像进行操作
 *
 * @author YuLei Liao <liaoyulei@qeeyuan.com>
 * @version $Id: ImageHelper.php 3558 2012-08-22 04:23:57Z lonestone $
 * @package helper
 */
class ImageGD
{
    /**
     * GD 资源句柄
     *
     * @var resource
     */
    public $_handle = null;

    /**
     * 构造函数
     *
     * @param resource $handle GD 资源句柄
     */
    function __construct($handle)
    {
        $this->_handle = $handle;
    }

    /**
     * 析构函数
     */
    function __destruct()
    {
    	$this->destroy();
    }

    /**
     * 快速缩放图像到指定大小（质量较差）
     *
     * @param int $width 新的宽度
     * @param int $height 新的高度
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function resize($width, $height)
    {
        if (is_null($this->_handle)) return $this;

        $dest = imagecreatetruecolor($width, $height);
        imagecopyresized($dest, $this->_handle, 0, 0, 0, 0,
            $width, $height,
            imagesx($this->_handle), imagesy($this->_handle));
        imagedestroy($this->_handle);
        $this->_handle = $dest;
        return $this;
    }

    /**
     * 缩放图像到指定大小（质量较好，速度比 resize() 慢）
     *
     * @param int $width 新的宽度
     * @param int $height 新的高度
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function resampled($width, $height)
    {
        if (is_null($this->_handle)) return $this;
        $dest = imagecreatetruecolor($width, $height);
        imagecopyresampled($dest, $this->_handle, 0, 0, 0, 0,
            $width, $height,
            imagesx($this->_handle), imagesy($this->_handle));
        imagedestroy($this->_handle);
        $this->_handle = $dest;
        return $this;
    }

    /**
     * 调整图像大小，但不进行缩放操作
     *
     * 用法：
     * @code php
     * $image->resizeCanvas($width, $height, 'top-left');
     * @endcode
     *
     * $pos 参数指定了调整图像大小时，图像内容按照什么位置对齐。
     * $pos 参数的可用值有：
     *
     * -   left: 左对齐
     * -   right: 右对齐
     * -   center: 中心对齐
     * -   top: 顶部对齐
     * -   bottom: 底部对齐
     * -   top-left, left-top: 左上角对齐
     * -   top-right, right-top: 右上角对齐
     * -   bottom-left, left-bottom: 左下角对齐
     * -   bottom-right, right-bottom: 右下角对齐
     *
     * 如果指定了无效的 $pos 参数，则等同于指定 center。
     *
     * @param int $width 新的高度
     * @param int $height 新的宽度
     * @param string $pos 调整时图像位置的变化
     * @param string $bgcolor 空白部分的默认颜色
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function resizeCanvas($width, $height, $pos = 'center', $bgcolor = '0xffffff')
    {
        if (is_null($this->_handle)) return $this;

        $dest = imagecreatetruecolor($width, $height);
        $sx = imagesx($this->_handle);
        $sy = imagesy($this->_handle);

        // 根据 pos 属性来决定如何定位原始图片
        switch (strtolower($pos))
        {
        case 'left':
            $ox = 0;
            $oy = ($height - $sy) / 2;
            break;
        case 'right':
            $ox = $width - $sx;
            $oy = ($height - $sy) / 2;
            break;
        case 'top':
            $ox = ($width - $sx) / 2;
            $oy = 0;
            break;
        case 'bottom':
            $ox = ($width - $sx) / 2;
            $oy = $height - $sy;
            break;
        case 'top-left':
        case 'left-top':
            $ox = $oy = 0;
            break;
        case 'top-right':
        case 'right-top':
            $ox = $width - $sx;
            $oy = 0;
            break;
        case 'bottom-left':
        case 'left-bottom':
            $ox = 0;
            $oy = $height - $sy;
            break;
        case 'bottom-right':
        case 'right-bottom':
            $ox = $width - $sx;
            $oy = $height - $sy;
            break;
        default:
            $ox = ($width - $sx) / 2;
            $oy = ($height - $sy) / 2;
        }

        list ($r, $g, $b) = ImageHelper::hex2rgb($bgcolor, '0xffffff');
        $bgcolor = imagecolorallocate($dest, $r, $g, $b);
        imagefilledrectangle($dest, 0, 0, $width, $height, $bgcolor);
        imagecolordeallocate($dest, $bgcolor);

        imagecopy($dest, $this->_handle, $ox, $oy, 0, 0, $sx, $sy);
        imagedestroy($this->_handle);
        $this->_handle = $dest;

        return $this;
    }

    /**
     * 在保持图像长宽比的情况下将图像裁减到指定大小
     *
     * crop() 在缩放图像时，可以保持图像的长宽比，从而保证图像不会拉高或压扁。
     *
     * crop() 默认情况下会按照 $width 和 $height 参数计算出最大缩放比例，
     * 保持裁减后的图像能够最大程度的充满图片。
     *
     * 例如源图的大小是 800 x 600，而指定的 $width 和 $height 是 200 和 100。
     * 那么源图会被首先缩小为 200 x 150 尺寸，然后裁减掉多余的 50 像素高度。
     *
     * 用法：
     * @code php
     * $image->crop($width, $height);
     * @endcode
     *
     * 如果希望最终生成图片始终包含完整图像内容，那么应该指定 $options 参数。
     * 该参数可用值有：
     *
     * -   fullimage: 是否保持完整图像
     * -   pos: 缩放时的对齐方式
     * -   bgcolor: 缩放时多余部分的背景色
     * -   enlarge: 是否允许放大
     * -   reduce: 是否允许缩小
     *
     * 其中 $options['pos'] 参数的可用值有：
     *
     * -   left: 左对齐
     * -   right: 右对齐
     * -   center: 中心对齐
     * -   top: 顶部对齐
     * -   bottom: 底部对齐
     * -   top-left, left-top: 左上角对齐
     * -   top-right, right-top: 右上角对齐
     * -   bottom-left, left-bottom: 左下角对齐
     * -   bottom-right, right-bottom: 右下角对齐
     *
     * 如果指定了无效的 $pos 参数，则等同于指定 center。
     *
     * $options 中的每一个选项都可以单独指定，例如在允许裁减的情况下将图像放到新图片的右下角。
     *
     * @code php
     * $image->crop($width, $height, array('pos' => 'right-bottom'));
     * @endcode
     *
     * @param int $width 新的宽度
     * @param int $height 新的高度
     * @param array $options 裁减选项
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function crop($width, $height, $options = array())
    {
        if (is_null($this->_handle)) return $this;

        $default_options = array(
            'fullimage' => false,
            'pos'       => 'center',
            'bgcolor'   => '0xfff',
            'enlarge'   => false,
            'reduce'    => true,
        	'transparent' => false,
        );
        $options = array_merge($default_options, $options);

        // 创建目标图像
        $dest = imagecreatetruecolor($width, $height);
        // 填充背景色
        list ($r, $g, $b) = ImageHelper::hex2rgb($options['bgcolor'], '0xffffff');
        $bgcolor = imagecolorallocate($dest, $r, $g, $b);
        imagefilledrectangle($dest, 0, 0, $width, $height, $bgcolor);
        imagecolordeallocate($dest, $bgcolor);
    	if($options['transparent'])
        {
        	imagecolortransparent($dest, $bgcolor);
        }
        
        // 根据源图计算长宽比
        $full_w = imagesx($this->_handle);
        $full_h = imagesy($this->_handle);
        $ratio_w = doubleval($width) / doubleval($full_w);
        $ratio_h = doubleval($height) / doubleval($full_h);


        if ($options['fullimage'])
        {
            // 如果要保持完整图像，则选择最小的比率
            $ratio = $ratio_w < $ratio_h ? $ratio_w : $ratio_h;
        }
        else
        {
            // 否则选择最大的比率
            $ratio = $ratio_w > $ratio_h ? $ratio_w : $ratio_h;
        }

        if (!$options['enlarge'] && $ratio > 1) $ratio = 1;
        if (!$options['reduce'] && $ratio < 1) $ratio = 1;

        // 计算目标区域的宽高、位置
        $dst_w = $full_w * $ratio;
        $dst_h = $full_h * $ratio;

        // 根据 pos 属性来决定如何定位
        switch (strtolower($options['pos']))
        {
        case 'left':
            $dst_x = 0;
            $dst_y = ($height - $dst_h) / 2;
            break;
        case 'right':
            $dst_x = $width - $dst_w;
            $dst_y = ($height - $dst_h) / 2;
            break;
        case 'top':
            $dst_x = ($width - $dst_w) / 2;
            $dst_y = 0;
            break;
        case 'bottom':
            $dst_x = ($width - $dst_w) / 2;
            $dst_y = $height - $dst_h;
            break;
        case 'top-left':
        case 'left-top':
            $dst_x = $dst_y = 0;
            break;
        case 'top-right':
        case 'right-top':
            $dst_x = $width - $dst_w;
            $dst_y = 0;
            break;
        case 'bottom-left':
        case 'left-bottom':
            $dst_x = 0;
            $dst_y = $height - $dst_h;
            break;
        case 'bottom-right':
        case 'right-bottom':
            $dst_x = $width - $dst_w;
            $dst_y = $height - $dst_h;
            break;
        case 'center':
        default:
            $dst_x = ($width - $dst_w) / 2;
            $dst_y = ($height - $dst_h) / 2;
        }

        imagecopyresampled($dest,  $this->_handle, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $full_w, $full_h);
        imagedestroy($this->_handle);
        $this->_handle = $dest;
		
        return $this;
    }
    
    /**
     * 裁边 百分比
     * @param decimal $percent
     * @return ImageGD|ImageGD
     */
    function cutedge($percent)
    {
        if (is_null($this->_handle)) return $this;
        
        $full_w = imagesx($this->_handle);
        $full_h = imagesy($this->_handle);
        
        $dst_w = $full_w * (1- $percent * 2);
        $dst_h = $full_h * (1- $percent * 2);
        
        // 创建目标图像
        $dest = imagecreatetruecolor($dst_w, $dst_h);

        // 根据 pos 属性来决定如何定位
        $src_x = $full_w * $percent;
        $src_y = $full_h * $percent;

        imagecopy($dest,  $this->_handle, 0, 0, $src_x, $src_y, $full_w, $full_h);
        imagedestroy($this->_handle);
        $this->_handle = $dest;

        return $this;
    }
    
    /**
     * 打水印
     * @param array $options
     * @return ImageGD|ImageGD|ImageGD
     */
    function waterMark($options = array())
    {
    	if (is_null($this->_handle)) return $this;
    	
    	$default_options = array(
			'type'=>'png', //水印类型 gif GIF类型水印 png PNG类型水印 text 文本类型水印 如果设置文本类型的水印并且使用 GD图片处理库，那么还需要 FreeType 库支持才能使用
    		'transparent' => 100, //水印融合度 设置 GIF 类型水印图片与原始图片的融合度，范围为 1～100的整数，数值越大水印图片透明度越低。PNG 类型水印本身具有真彩透明效果，无须此设置
			'minwidth'=>0, //小于此尺寸的图片附件将不添加水印，0 以水印图片大小为添加条件
    		'minheight'=>0, 
    		'text' => array(
		    	'text'=>Yii::app()->request->serverName, //文本水印文字
		    	'fontpath'=> Yii::app()->basePath . '/data/DroidSansFallback.ttf',//文本水印 TrueType 字体文件名 填写存放在 /protected/data/ 目录下的 TTF 字体文件，支持中文字体。如使用中文 TTF 字体请使用包含完整中文汉字的字体文件。
		    	'size'=>10,//设置文本水印字体大小，请按照字体设置相应的大小
		    	'angle'=>0,//0 度为从左向右阅读文本
		    	'color'=>'#efefef',//16 进制颜色代表文本水印字体颜色
		        'shadowx'=>0, //设置文本水印阴影横向偏移量，此数值不宜设置的太大
		        'shadowy'=>0, //设置文本水印阴影纵向偏移量，此数值不宜设置的太大
		        'shadowcolor'=>'#999999',//16 进制颜色代表文本水印阴影字体颜色
		        'skewx'=>0, //设置水印文本横向的倾斜角度(ImageMagick)
		        'skewy'=>0, //文本水印纵向倾斜角度(ImageMagick)
		    ),
		    'position' => 9, //int 水印位置设置  1-9 左上 上中 右上 以此类推
        );
        
        $map = new CMap($default_options);
		$map->mergeWith($options);
		$options = $map->toArray();
        
        $img_w = imagesx($this->_handle);
        $img_h = imagesy($this->_handle);
		
        if(($options['minwidth'] && $img_w <= $options['minwidth']) || ($options['minheight'] && $img_h <= $options['minheight'])) 
        {
        	return $this; //宽度太小
        }

        if($options['type'] == 'text' && (!file_exists($options['text']['fontpath']) || !is_file($options['text']['fontpath']))) //字体检查
        	throw new Exception('The Font Path is invalid!');

		if(function_exists('imagecopy') && function_exists('imagealphablending') && function_exists('imagecopymerge')) 
		{
			if($options['type']!='text') {
				$watermark_file = $options['type'] == 'png' ? Yii::app()->basePath.'/../images/watermark.png' : Yii::app()->basePath.'/../images/watermark.gif';
		        $watermarkinfo	= @getimagesize($watermark_file);
				$watermark_logo	= $options['type'] == 'png' ? @imageCreateFromPNG($watermark_file) : @imageCreateFromGIF($watermark_file);
				if(!$watermark_logo) {
					throw new Exception('The watermark image is invalid!');
				}
				list($logo_w, $logo_h) = $watermarkinfo;
			} else {
				$watermarktextcvt = pack("H*", bin2hex($options['text']['text']));
				$box = imagettfbbox($options['text']['size'], $options['text']['angle'], $options['text']['fontpath'], $watermarktextcvt);
				$logo_h = max($box[1], $box[3]) - min($box[5], $box[7]);
				$logo_w = max($box[2], $box[4]) - min($box[0], $box[6]);
				$ax = min($box[0], $box[6]) * -1;
   				$ay = min($box[5], $box[7]) * -1;
			}
			$wmwidth = $img_w - $logo_w;
			$wmheight = $img_h - $logo_h;
			
			if(($options['type']!='text' && is_readable($watermark_file) || $options['type']=='text') && $wmwidth > 10 && $wmheight > 10) 
			{
			    switch($options['position']) {
					case 1:
						$x = +5;
						$y = +5;
						break;
					case 2:
						$x = ($img_w - $logo_w) / 2;
						$y = +5;
						break;
					case 3:
						$x = $img_w - $logo_w - 5;
						$y = +5;
						break;
					case 4:
						$x = +5;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 5:
						$x = ($img_w - $logo_w) / 2;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 6:
						$x = $img_w - $logo_w;
						$y = ($img_h - $logo_h) / 2;
						break;
					case 7:
						$x = +5;
						$y = $img_h - $logo_h - 5;
						break;
					case 8:
						$x = ($img_w - $logo_w) / 2;
						$y = $img_h - $logo_h - 5;
						break;
					case 9:
						$x = $img_w - $logo_w - 5;
						$y = $img_h - $logo_h - 5;
						break;
				}

				$dst_photo = imagecreatetruecolor($img_w, $img_h);
				@imageCopy($dst_photo, $this->_handle, 0, 0, 0, 0, $img_w, $img_h);

				if($options['type'] == 'png') {
					@imageCopy($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h);
				} elseif($options['type'] == 'text') {
					if(($options['text']['shadowx'] || $options['text']['shadowy']) && $options['text']['shadowcolor']) {
						$shadowcolorrgb = ImageHelper::hex2rgb($options['text']['shadowcolor']);
						$shadowcolor = imagecolorallocate($dst_photo, $shadowcolorrgb[0], $shadowcolorrgb[1], $shadowcolorrgb[2]);
						imagettftext($dst_photo, $options['text']['size'], $options['text']['angle'], $x + $ax + $options['text']['shadowx'], $y + $ay + $options['text']['shadowy'], $shadowcolor, $options['text']['fontpath'], $watermarktextcvt);
					}
					$colorrgb = ImageHelper::hex2rgb($options['text']['color']);
					$color = imagecolorallocate($dst_photo, $colorrgb[0], $colorrgb[1], $colorrgb[2]);
					imagettftext($dst_photo, $options['text']['size'], $options['text']['angle'], $x + $ax, $y + $ay, $color, $options['text']['fontpath'], $watermarktextcvt);
				} else {
					imageAlphaBlending($watermark_logo, true);
					@imageCopyMerge($dst_photo, $watermark_logo, $x, $y, 0, 0, $logo_w, $logo_h, $options['transparent']);
				}
				
				$this->_handle = $dst_photo;
			}
		}	
 		else
 			throw new Exception('The GD library seams to be disabled!');
    }
    
    /**
     * 把坐标 x1，y1 到 x2，y2（图像左上角为 0, 0）的矩形区域加上马赛克。deep为模糊程度，数字越大越模糊。
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @param int $deep
     */
    public function mosaic($x1, $y1, $x2, $y2, $deep) 
    {
    	if($x1 >= $x2 || $y1 >= $y2) return $this;
	    for($x = $x1; $x < $x2; $x += $deep) {
	        for ($y = $y1; $y < $y2; $y += $deep) {
	            $color = @ImageColorAt($this->_handle, $x + round($deep / 2), $y + round($deep / 2));
	            @imagefilledrectangle($this->_handle, $x, $y, $x + $deep, $y + $deep, $color);
	        }
	    }
	}
    

    /**
     * 保存为 JPEG 文件
     *
     * @param string $filename 保存文件名
     * @param int $quality 品质参数，默认为 80
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function saveAsJpeg($filename, $quality = 95)
    {
        imagejpeg($this->_handle, $filename, $quality);
    }
    
	function saveAsJpg($filename)
    {
        $this->saveAsJpeg($filename);
    }

    /**
     * 保存为 PNG 文件
     *
     * @param string $filename 保存文件名
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function saveAsPng($filename)
    {
        imagepng($this->_handle, $filename);
    }

    /**
     * 保存为 GIF 文件
     *
     * @param string $filename 保存文件名
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function saveAsGif($filename)
    {
        imagegif($this->_handle, $filename);
    }
    
    /**
     * 保存为 BMP 文件
     *
     * @param string $filename 保存文件名
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function saveAsBmp($filename)
    {
        imagewbmp($this->_handle, $filename);
    }
    
     /**
     * 根据扩展名保存
     *
     * @param string $filename 保存文件名
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function save($filename)
    {
    	$pathinfo = pathinfo($filename);
    	$ext = $pathinfo['extension'] ? $pathinfo['extension'] : 'jpg';
    	$filename = $pathinfo['extension'] ? $filename : $filename.'.jpg';
    	$func = 'saveAs'.$ext;
    	$this->$func($filename);
    }

    /**
     * 销毁内存中的图像
     *
     * @return ImageGD 返回 ImageGD 对象本身，实现连贯接口
     */
    function destroy()
    {
    	if (!$this->_handle)
    	{
            @imagedestroy($this->_handle);
    	}
        $this->_handle = null;
        return $this;
    }
}

