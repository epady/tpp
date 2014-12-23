<?php 
$this->pageTitle = '欢迎使用'.Yii::app()->name.'后台管理系统';

$this->breadcrumbs=array(
        '获取最新订单',
);
?>

<style>
.btn-max{ font-size: 16px;padding: 10px 10px;margin: 10px 0;}
h2{ text-align: center;padding: 30px 20px;}
</style>

<h2>目前正在队列的订单有： <span style="font-size:20px;color:red;"><?php echo $number; ?></span> 单 <?php echo CHtml::link('刷新', array('/site/welcome'),array('class'=>'btn')); ?></h2>



<p style="text-align:center">
<?php if($user->order_status): ?>
<?php echo CHtml::link('您有订单正在处理',array('/order/update','id'=>$user->order_id),array('class'=>'btn btn-max')); ?>
<?php else: ?>
<?php echo CHtml::link('获取最新订单',array('order/get'),array('class'=>'btn btn-max')); ?>
<?php endif; ?>
</p>