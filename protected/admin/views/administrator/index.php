<?php
$this->breadcrumbs=array(
	'后台用户管理',
);

$this->menu=array(
	array('label'=>'管理后台用户', 'url'=>array('administrator/index')),
	array('label'=>'添加后台用户', 'url'=>array('administrator/create')),
);

?>

<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>

<?php $this->widget('GridView', array(
	'id'=>'administrator-grid',
	'dataProvider'=>$model->search(),
	'selectableRows' => 2,
	'itemsCssClass' => 'tb2',
	'cssFile'=>false,
	'columns'=>array(
		'id',
		'username',
		'email',
		'roleName',
        'last_login_time:datetime',
        'last_login_ip',
		array(
			'class'=>'ButtonColumn',
			'viewButtonOptions' => array('target'=>'_self'),
		),
	),
)); ?>
