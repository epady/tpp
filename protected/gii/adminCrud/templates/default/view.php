<?php
echo "<?php\n";
echo "\$label = CActiveRecord::model('$this->modelClass')->modelName;\n";
?>

$this->breadcrumbs=array(
	$label.'管理',
    '查看'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'新增'.$label, 'url'=>array('create')),
	array('label'=>'修改'.$label, 'url'=>array('update','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'查看'.$label, 'url'=>array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>), 'active'=>true),
    array('label'=>'删除'.$label, 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'您确定要删除该项目吗？', 'csrf'=>true)),
);
?>

  <table class="tb tb2 nobdb">
    <tr>
      <th colspan="15" class="partition"><?php echo $label;?>详细资料</th>
    </tr>
    </table>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class'=>'tb tb2 nobdb'),
	'itemCssClass' => array('hover odd', 'hover even'),
	'itemTemplate' => '<tr class="{class}" ><td class="td21">{label}：</td><td>{value}</td></tr>',
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
{
	if($this->checkExists($column->name, $this->image_fields))
		echo "\t\t'".$column->name.":image',\n";
	elseif($this->checkExists($column->name, $this->url_fields))
		echo "\t\t'".$column->name.":url',\n";
	elseif(strpos($column->name, 'is_') !== false)
		echo "\t\t'".$column->name.":boolean',\n";
	else
		echo "\t\t'".$column->name."',\n";
}
?>
	),
)); ?>