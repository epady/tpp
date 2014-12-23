<?php
$label = CActiveRecord::model('Member')->modelName;

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
    'id'=>'-grid',
    'dataProvider'=>$model->search(),
    'selectableRows' => 2,
    'itemsCssClass' => 'tb2',
    'rowCssClass' => array('hover odd', 'hover even'),
    'pager' => array('class'=>'CombPager'),
    //'template' => '{items} <div class="pure-g"><div class="pure-u-2-3">{pager}</div><div class="pure-u-1-3">{summary}</div></div>',
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
			'phone',
			'username',
            'email',
			'integral',
			'created:datetime',
			'updated:datetime',
			
        array(
            'header' => '操作',
            'class'=>'ButtonColumn',
        ),
    ),
)); ?>
