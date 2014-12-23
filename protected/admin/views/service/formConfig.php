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
        'name'=>array(
            'type'=>'text',
        ),
        'shop_id'=>array(
            'type'=>'dropdownlist',
            'items' => CHtml::listData(Shop::model()->findAll(),'id','name'),
        ),
        'price'=>array(
            'type'=>'text',
        ),
        'tags'=>array(
            'type'=>'text',
        ),
        'hint'=>array(
            'type'=>'text',
        ),
        'awaytime'=>array(
            'type'=>'text',
            'hint' => '推荐填写：半天，全天，二天，三天'
        ),
        'photo_count'=>array(
            'type'=>'text',
        ),
        'base_count'=>array(
            'type'=>'text',
        ),
        'dipian'=>array(
            'type'=>'dropdownlist',
            'items' => Service::dipianOptions(),
            'attributes' => array(
                'empty' => '请选择底片处理方式'
            )
        ),
        'content'=>array(
            'type'=>'textarea',
			'attributes'=>array('style'=>'width:500px;height:80px;'),
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