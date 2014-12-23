<?php
$label = CActiveRecord::model('Order')->modelName;

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
    'cssFile'=>false,
    'columns'=>array(
			'id',
			'member.phone',
			'sn',
			'statusStr',
            'order_price',
            'service',
            'gw',
			'is_diff:boolean',
            'created:datetime',
        array(
            'header' => '操作',
            'class'=>'ButtonColumn',
            
            'viewButtonOptions' => array('target'=>'_self'),
            'template' => '{view} {channel}',
            'buttons' => array(
                'channel'=>array(
                'label'=>'取消订单',
                'visible'=>'$data->status == "0"',
                'url'=>'Yii::app()->controller->createUrl("channel", array("id"=>$data->primaryKey))',
                ),
            ),
            
        ),
    ),
)); ?>