<?php
$label = CActiveRecord::model('MemberMoney')->modelName;

$this->breadcrumbs=array(
        $label.'管理',
);

$this->menu=array(
        array('label'=>'管理'.$label, 'url'=>array('index'), 'active'=>true),
        array('label'=>'我要充值', 'url'=>array('create')),
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
			'id',
			array(
        'header' => '用户',
        'name' => 'member.phone',
      ),
			'inmoney',
			'outmoney',
			'money',
			'admin.username:充值人员',
			'content',
			'created:datetime',
			
        array(
            'header' => '操作',
            'class'=>'ButtonColumn',
            'viewButtonOptions' => array('target'=>'_self'),
            'template' => '{view}',
            
        ),
    ),
)); ?>

