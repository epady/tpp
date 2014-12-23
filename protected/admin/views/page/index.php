<?php
$label = CActiveRecord::model(Page)->modelName;
$this->breadcrumbs=array(
	$label.'管理',
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index'), 'active'=>true),
	array('label'=>'添加'.$label, 'url'=>array('create')),
);

?>

<table class="tb tb2 " id="tips">
  <tr>
    <th  class="partition">技巧提示</th>
  </tr>
  <tr >
    <td class="tipsblock" ><ul id="tipslis">
        <li>您可在此添加、删除、管理网站的<?php echo CActiveRecord::model('Page')->modelName ?>。</li>
      </ul></td>
  </tr>
</table>


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<table class="tb tb2 nobdt search_header">
    <tr>
        <th colspan="15" class="partition">搜索符合条件的数据</th>
    </tr>
    <tr>
        <td>
      
    <table class="noborder">
		<tr>
            <td style="text-align:right;vertical-align:middle;"><?php echo $form->label($model,'id'); ?></td>
            <td><?php echo $form->textField($model,'id', array('size'=>10)); ?></td>
            <td style="text-align:right;vertical-align:middle;"><?php echo $form->label($model,'category'); ?></td>
            <td><?php echo $form->dropdownlist($model,'category',Page::cates(), array( 'empty'=>'全部类别')); ?></td>
            <td style="text-align:right;vertical-align:middle;"><?php echo $form->label($model,'title'); ?></td>
            <td><?php echo $form->textField($model,'title',array('size'=>20,'maxlength'=>90)); ?></td>

            <td>
            <?php echo CHtml::submitButton('搜索', array('class'=>'btn')); ?>
            </td>
        </tr>
    </table>
    
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<!-- search-form -->


<?php $this->widget('GridView', array(
	'id'=>'page-grid',
	'dataProvider'=>$model->search(),
	'itemsCssClass' => 'tb2',
	'selectableRows' => 2,
	'cssFile'=>false,
    'pager' => array('class'=>'CombPager'),
	'columns'=>array(
		array(
			'class'=>'CCheckBoxColumn',
			'name'=>'id',
			'id' => 'id',
			'headerHtmlOptions' => array('style'=>'width:40px'),
			'checkBoxHtmlOptions'=>array('class'=>'checkbox'),
		),
		'id',
		'category',
		'title',
		'name',
		//'content',
		array(
			'class'=>'ButtonColumn',
			'viewButtonOptions' => array('target'=>'_self'),
		),
	),
)); ?>
