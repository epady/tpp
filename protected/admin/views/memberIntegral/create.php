<?php
$label = CActiveRecord::model('MemberIntegral')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '新增'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'新增'.$label, 'url'=>array('create'), 'active'=>true),
);
?>

<table class="tb tb2 " id="tips">
  <tr>
    <th  class="partition">技巧提示</th>
  </tr>
  <tr >
    <td class="tipsblock" >
    	<ul id="tipslis">
        <li>添加，请完整填写各类信息。</li>
      	</ul>
      </td>
  </tr>
</table>

    
<?php echo $form; ?>