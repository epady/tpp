<?php
$this->breadcrumbs=array(
	'后台用户管理',
);

$this->menu=array(
	array('label'=>'管理后台用户', 'url'=>array('administrator/index')),
	array('label'=>'添加后台用户', 'url'=>array('administrator/create')),
	array('label'=>'修改后台用户', 'url'=>array('update', 'id'=>$model->id)),
);

?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class'=>'tb tb2 nobdb'),
	'itemCssClass' => array('hover odd', 'hover even'),
	'itemTemplate' => '<tr class="{class}" ><td width="120">{label}</td><td>{value}</td></tr>',
	'attributes'=>array(
		'id',
		'username',
		'email',
        'last_login_time:datetime',
        'last_login_ip',
        'this_login_time:datetime',
        'this_login_ip',
        'roleName',
	),
)); ?>
