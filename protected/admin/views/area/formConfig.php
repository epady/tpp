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
        'parent_id'=>array(
            'type'=>'dropdownlist',
            'items' => Area::options(0),
            'attributes' => array(
                'empty' => '省(直辖市)',
            ),
        ),
        'name'=>array(
            'type'=>'text',
        ),
        'is_open'=>array(
            'type'=>'dropdownlist',
            'items' => array(
                '0' => '否',
                '1' => '是',
            ),
        ),
        'sort_order'=>array(
            'type'=>'text',
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