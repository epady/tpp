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
        'phone'=>array(
            'type'=>'text',
        ),
        'password'=>array(
            'type'=>'password',
        ),
        'email'=>array(
            'type'=>'text',
        ),
        'city_id'=>array(
            'type'=>'dropdownlist',
            'items' => Area::options(),
            'attributes' => array(
                'empty' => '全部地区'
            ),
        ),
        'company_id'=>array(
            'type'=>'dropdownlist',
            'items' => Company::options(),
            'attributes' => array(
                'empty' => '指定机构'
            ),
        ),        
        'avatar'=>array(
            'type'=>'ext.FileFieldWidget',
			'enableAjaxValidation'=>false,
			'thumbOptions'=>array('fullimage'=>true, 'width'=>200, 'height'=>200),
        ),
        'image'=>array(
            'type'=>'ext.FileFieldWidget',
			'enableAjaxValidation'=>false,
			'thumbOptions'=>array('fullimage'=>true, 'width'=>200, 'height'=>200),
        ),
        'sign'=>array(
            'type'=>'text',
        ),
        'status'=>array(
            'type'=>'dropdownlist',
            'items' => Shop::statusOptions()
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