<?php
$label = CActiveRecord::model('Activity')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '修改'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'新增'.$label, 'url'=>array('create')),
	array('label'=>'修改'.$label, 'url'=>array('update','id'=>$form->model->id), 'active'=>true),
	array('label'=>'查看'.$label, 'url'=>array('view','id'=>$form->model->id)),
	array('label' => '删除'.$label, 'url' => '#', 'linkOptions' => array(
		'submit' => array('delete', 'id' => $model->id),
		'confirm' => '您真的要删除这条数据吗？',
	)),
);

?>

<table class="tb tb2 " id="tips">
  <tr>
    <th  class="partition">技巧提示</th>
  </tr>
  <tr >
    <td class="tipsblock" ><ul id="tipslis">
        <li>添加，请完整填写各类信息。</li>
      </ul></td>
  </tr>
</table>

<?php echo $form; ?>
