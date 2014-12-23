<?php
$label = CActiveRecord::model('Log')->modelName;

$this->breadcrumbs=array(
        $label.'管理',
);

$this->menu=array(
        array('label'=>'管理'.$label, 'url'=>array('index'), 'active'=>true),
);
?>


<?php $this->renderPartial('_search',array('model'=>$model)); ?>

<!-- search-form -->



<?php $this->widget('GridView', array(
    'id'=>'log-grid',
    'dataProvider'=>$model->search(),
    'selectableRows' => 2,
    'itemsCssClass' => 'tb2',
    'rowCssClass' => array('hover odd', 'hover even'),
    'pager' => array('class'=>'CombPager'),
    'cssFile'=>false,
    'columns'=>array(
        array(
                'class'=>'CCheckBoxColumn',
                'name'=>'id',
                'id' => 'id',
                'headerHtmlOptions' => array('style'=>'width:40px'),
                'checkBoxHtmlOptions'=>array('class'=>'checkbox'),
            ),
			'id',
			'date',
			'ip',
			'operator_id',
			'operator_name',
			'type',
			'category',
			//'description',
			//'model',
			'model_pk',
			
        array(
            'header' => '操作',
            'class'=>'ButtonColumn',
            'viewButtonOptions' => array('target'=>'_self'),
            'template' => '{view}',
        ),
    ),
)); ?>