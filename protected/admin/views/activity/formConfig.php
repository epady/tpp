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
        'city_id'=>array(
            'type'=>'dropdownlist',
            'items' => Area::options(0),
            'attributes' => array(
                'empty' => '全国地区共有',
            ),
        ),
        'name'=>array(
            'type'=>'text',
        ),
        'image'=>array(
            'type'=>'ext.FileFieldWidget',
			'enableAjaxValidation'=>false,
			'thumbOptions'=>array('fullimage'=>true, 'width'=>200, 'height'=>200),
        ),
        'content'=>array(
            'type'=>'textarea',
			'attributes'=>array('class'=>'ckeditor'),
        ),
        'status'=>array(
            'type'=>'dropdownlist',
            'items' => Activity::statusOptions()
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