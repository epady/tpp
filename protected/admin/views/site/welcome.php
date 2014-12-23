<?php 
$this->pageTitle = '欢迎使用'.Yii::app()->name.'后台管理系统';

$this->breadcrumbs=array(
        '首页统计',
);

?>

<table class="tb tb2">
	<tr>
		<th colspan="8" class="partition">订单统计</th>
	</tr>
	<tr>
		<td width="80">新增订单</td>
		<td>0</td>
		<td width="80">未完成订单</td>
		<td>0</td>
		<td width="80">审核</td>
		<td>0 条</td>
		<td width="80">待审核</td>
		<td>0 条</td>
	</tr>
</table>