<?php
Yii::import('zii.widgets.grid.CGridView');
class GridView extends CGridView
{
	public $template="{items}\n{pager}";
	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		if(!$this->hideHeader)
		{
			echo "<thead>\n";
			echo '<tr><th colspan="20" class="partition">搜索结果列表 (';
			$this->renderSummary();
			echo ')</th></tr>';

			if($this->filterPosition===self::FILTER_POS_HEADER)
				$this->renderFilter();

			echo "<tr class=\"header\">\n";
			foreach($this->columns as $column)
				$column->renderHeaderCell();
			echo "</tr>\n";

			if($this->filterPosition===self::FILTER_POS_BODY)
				$this->renderFilter();

			echo "</thead>\n";
		}
		else if($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY))
		{
			echo "<thead>\n";
			$this->renderFilter();
			echo "</thead>\n";
		}
	}
	
	/**
	 * Renders a table body row.
	 * @param integer $row the row number (zero-based).
	 */
	public function renderTableRow($row)
	{
		if($this->rowCssClassExpression!==null)
		{
			$data=$this->dataProvider->data[$row];
			echo '<tr class="hover '.$this->evaluateExpression($this->rowCssClassExpression,array('row'=>$row,'data'=>$data)).'">';
		}
		else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0)
			echo '<tr class="hover '.$this->rowCssClass[$row%$n].'">';
		else
			echo '<tr class="hover">';
		foreach($this->columns as $column)
			$column->renderDataCell($row);
		echo "</tr>\n";
	}
	
/**
	 * Renders the summary text.
	 */
	public function renderSummary()
	{
		if(($count=$this->dataProvider->getItemCount())<=0)
			return;

		echo '<span class="'.$this->summaryCssClass.'">';
		if($this->enablePagination)
		{
			if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.');
			$pagination=$this->dataProvider->getPagination();
			$total=$this->dataProvider->getTotalItemCount();
			$start=$pagination->currentPage*$pagination->pageSize+1;
			$end=$start+$count-1;
			if($end>$total)
			{
				$end=$total;
				$start=$end-$count+1;
			}
			echo strtr($summaryText,array(
				'{start}'=>$start,
				'{end}'=>$end,
				'{count}'=>$total,
				'{page}'=>$pagination->currentPage+1,
				'{pages}'=>$pagination->pageCount,
			));
		}
		else
		{
			if(($summaryText=$this->summaryText)===null)
				$summaryText=Yii::t('zii','Total {count} result(s).');
			echo strtr($summaryText,array(
				'{count}'=>$count,
				'{start}'=>1,
				'{end}'=>$count,
				'{page}'=>1,
				'{pages}'=>1,
			));
		}
		echo '</span>';
	}
}