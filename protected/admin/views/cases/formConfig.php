<?php 
return array(
	'activeForm'=> array(
		'id'=>'edit-form',
		'enableAjaxValidation'=>true,
		'htmlOptions'=> array('enctype'=>'multipart/form-data'),
		'clientOptions'=>array(
			'validateOnSubmit'=>true, 
			'validateOnChange'=>true,
			'beforeValidate'=>'js:function(form){
				$.each(CKEDITOR.instances, function(i, editor){
					editor.updateElement();
				});
				return true;
			}',
		),
	),
	'elements'=>array(
        'title'=>array(
            'type'=>'text',
        ),
        'image'=>array(
            'type'=>'ext.FileFieldWidget',
			'enableAjaxValidation'=>false,
			'thumbOptions'=>array('fullimage'=>true, 'width'=>200, 'height'=>200),
        ),
        'shop_id'=>array(
            'type'=>'dropdownlist',
            'items' => CHtml::listData(Shop::model()->findAll(),'id','name'),
            'attributes' => array(
                'empty' => '请指定摄影师',
            )
        ),
        'content'=>array(
            'type'=>'textarea',
			'attributes'=>array('class'=>'ckeditor'),
        ),
        'tags'=>array(
            'type'=>'text',
            'hint' => '多标签请使用空隔隔开'
        ),
           	),      
	'buttons'=>array(
        'btnsubmit'=>array(
            'type'=>'submit',
            'label'=>'确定',
    		'class'=>'btn',
        ),
    ),
);  