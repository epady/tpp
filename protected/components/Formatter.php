<?php

class Formatter extends CFormatter
{
    public $dateFormat='Y-m-d';
    public $datetimeFormat='Y-m-d H:i:s ';
    public $booleanFormat=array('否','是');
    
	/**
	 * Formats the array value as readable format.
	 * @param mixed $value the value to be formatted
	 * @return string the formatted result
	 */
	public function formatArray($value)
	{
		if(!empty($value) && is_array($value))
		{
			$html .= '<table>';
			foreach ($value as $name => $val)
			{
				$html .= CHtml::tag('tr', array(), '<td style="font-weight:normal">'.$name .'：</td><td>'.nl2br($val).'</td>');
			}
			$html .='</table>';
			return $html;
		}
		else 
			return $value;
	}
	
	/**
	 * 与框架自带的区别是不判断是否有http://
	 * Formats the value as a hyperlink.
	 * @param mixed $value the value to be formatted
	 * @return string the formatted result
	 */
	public function formatUrl($value)
	{
		return CHtml::link(CHtml::encode($value),$value);
	}

	public function formatMaskIDSN($value)
	{
		$pattern = '/^(\d{3})(\d{8,11})(\d{4})$/';
		return preg_replace($pattern, '$1***********$3', $value);
	}

	public function formatMaskMobile($value)
	{
		$pattern = '/^(\d{7})(\d{4})$/';
		return preg_replace($pattern, '*******$2', $value);
	}

	public function formatMaskName($value)
	{
		$len = mb_strlen($value, Yii::app()->charset);
		$start = ($len >2) ? mb_substr($value, 0, 1, Yii::app()->charset) : '';
		$end = mb_substr($value, -1, 1, Yii::app()->charset);
		return $start . str_repeat('*', $len - 1 - mb_strlen($start, Yii::app()->charset)).$end;
	}

	public function formatMaskAddress($value)
	{
		$len = mb_strlen($value, Yii::app()->charset);
		$start = ($len >3) ? mb_substr($value, 0, 2, Yii::app()->charset) : '';
		$end = mb_substr($value, -2, 2, Yii::app()->charset);
		return $start . str_repeat('*', $len - 2 - mb_strlen($start, Yii::app()->charset)).$end;
	}
	
	public function formatTable($value)
	{
		preg_match_all('/##(.+?)##/is', $value, $groups, PREG_PATTERN_ORDER);
		$params = preg_split('/##(.+?)##/is', $value, null, PREG_SPLIT_NO_EMPTY);
		$html = '<ul id="p_top">';
		foreach($groups[1] as $index=>$group)
		{
			$html .= "<li><a href=\"#p{$index}\">{$group}</a></li>";
		}

		$html .= '</ul><div class="clear mb10"></div><table class="ui-table ui-table-inbox">';
		foreach($groups[1] as $index=>$group)
		{
			$html .= "<tbody><tr class=\"u_buy_row\"><td colspan=\"2\" class=\"tal\">{$group}&nbsp;<a id=\"p{$index}\" href=\"#p_top\" class=\"linkgay1\">top</a></td></tr>";

			$str = trim($params[$index]);
			$lines = explode("\r\n", $str);
			$items = array();
			foreach($lines as $line)
			{
				$pairs = explode('#', $line);
				$items[$pairs[0]] = $pairs[1];
			}

			foreach($items as $name=>$val)
			{
				// $val = self::formatNText($val);
				$html .= "<tr class=\"u_buy_order\"><th width=\"120\">{$name}</th><td class=\"tal\">{$val}</td></tr>";
			}
			$html .='</tbody>';
		}
		$html .= '</table>';

		return $html;
	}
}