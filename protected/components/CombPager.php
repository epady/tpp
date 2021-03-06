<?php
/**
 * CombPager class file
 * @author lonestone@qq.com
 * @link http://www.paichewang.com/
 * @copyright Copyright &copy; 2010 paichewang.com
 *
 */
class CombPager extends CLinkPager
{
	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		$this->registerClientScript();
		$buttons=$this->createPageButtons();
		if(empty($buttons))
			return;
		$html = CHtml::tag('div', array('class'=>'links'),CHtml::tag('ul',$this->htmlOptions,implode("\n",$buttons)));
		$html .= CHtml::tag('div', array('class'=>'jumpbox'),$this->createJumpBox());
		
		echo CHtml::tag('div', array('class'=>'combPager'), $html);
	}


	/**
	 * @return string 生成跳转表单
	 */
	protected function createJumpBox()
	{
		$currentPage=$this->getCurrentPage(false);
		$input = CHtml::textField($this->pages->pageVar, $currentPage+1, array('class'=>'pagebox','onkeydown'=>'if(event.keyCode==13) {$(\'#pager-form\').submit();}'));
		//$button = CHtml::submitButton('确定', array('name'=>'jumpbtn', 'class'=>'btn'));
		//url参数
		$params=$this->pages->params===null ? $_GET : $this->pages->params;
		unset($params[$this->pages->pageVar]);
		unset($params['jumpbtn']);
		$hidden = '';
		foreach($params as $name => $value)
		{
			if(!is_array($value))
			{
				$hidden .= 	CHtml::hiddenField($name, $value);
			}
			else
			{
				foreach($value as $key=>$val)
				{
					$hidden .= 	CHtml::hiddenField("{$name}[{$key}]", $val);
				}
			}
		}
		
		return CHtml::tag('form', array('id'=>'pager-form', 'method'=>'get'), "到第{$input}页{$button}{$hidden}");
	}
	
	/**
	 * Registers the needed client scripts (mainly CSS file).
	 */
	public function registerClientScript()
	{
		if($this->cssFile!==false)
			self::registerCssFile($this->cssFile);
	}

	/**
	 * Registers the needed CSS file.
	 * @param string $url the CSS URL. If null, a default CSS URL will be used.
	 * @since 1.0.2
	 */
	public static function registerCssFile($url=null)
	{
		if($url===null)
			$url = Yii::app()->baseUrl . '/css/combpager.css';
		Yii::app()->getClientScript()->registerCssFile($url);
	}
	
	/**
	 * Creates the page buttons.
	 * 增加了前后省略号
	 * @return array a list of page buttons (in HTML code).
	 */
	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		list($beginPage,$endPage)=$this->getPageRange();
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons=array();

		// first page
		$buttons[]=$this->createPageButton($this->firstPageLabel,0,self::CSS_FIRST_PAGE,$currentPage<=0,false);

		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
		$buttons[]=$this->createPageButton($this->prevPageLabel,$page,self::CSS_PREVIOUS_PAGE,$currentPage<=0,false);

		// internal pages
		if($beginPage>0) $buttons[]=$this->createPageButton('...',$beginPage-1,self::CSS_INTERNAL_PAGE,false,false);
		
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,self::CSS_INTERNAL_PAGE,false,$i==$currentPage);

		if($endPage+2<=$pageCount) $buttons[]=$this->createPageButton('...',$endPage+1,self::CSS_INTERNAL_PAGE,false,false);
		
		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
		$buttons[]=$this->createPageButton($this->nextPageLabel,$page,self::CSS_NEXT_PAGE,$currentPage>=$pageCount-1,false);

		// last page
		$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,self::CSS_LAST_PAGE,$currentPage>=$pageCount-1,false);

		return $buttons;
	}
}