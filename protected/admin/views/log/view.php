<?php
$label = CActiveRecord::model('Log')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '查看'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'查看'.$label, 'url'=>array('view','id'=>$model->id), 'active'=>true),
);
?>

  <table class="tb tb2 nobdb">
    <tr>
      <th colspan="15" class="partition">详细资料</th>
    </tr>
    </table>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class'=>'tb tb2 nobdb'),
	'itemCssClass' => array('hover odd', 'hover even'),
	'itemTemplate' => '<tr class="{class}" ><td class="td21">{label}：</td><td>{value}</td></tr>',
	'attributes'=>array(
		'id',
		'date',
		'ip',
		'operator_id',
		'operator_name',
		'type',
		'category',
		'description:raw',
		'is_delete:boolean',
		'model',
		'model_pk',
	),
)); ?>