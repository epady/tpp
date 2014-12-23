<?php
$label = CActiveRecord::model('Service')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '修改'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'新增'.$label, 'url'=>array('create')),
	array('label'=>'修改'.$label, 'url'=>array('update','id'=>$form->model->id), 'active'=>true),
	array('label'=>'查看'.$label, 'url'=>array('view','id'=>$form->model->id)),
	
);

?>


<?php echo $form; ?>
