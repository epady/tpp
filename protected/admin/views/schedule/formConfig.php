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
        'date'=>array(
            'type'=>'zii.widgets.jui.CJuiDatePicker',
			'htmlOptions'=>array('size'=>12),
            'language'=>'zh-CN',
			'options'=>array(
				//'minDate'=>'js:new Date()',
				'changeMonth'=>'js:true',
				'changeYear'=>'js:true',
				//'numberOfMonths'=>2,
			),
        ),
        'user_id'=>array(
            'type'=>'text',
        ),
        'service_id'=>array(
            'type'=>'dropdownlist',
            'items' => CHtml::listData(Service::model()->findAll(),'id','name'),
            'attributes' => array(
            	'empty' => '选择服务套系'
            ),
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