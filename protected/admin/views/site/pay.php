<?php 
$this->pageTitle = '欢迎使用'.Yii::app()->name.'后台管理系统';

$this->breadcrumbs=array(
        '首页统计',
);

?>


<p style="text-align:center">
	
	<?php echo CHtml::link('我要充值',array('memberMoney/create'),array('class'=>'btn')); ?>

</p>