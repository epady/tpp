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
        'category'=>array(
			'type'=>'dropdownlist',
			'items'=>Page::cates(),
			'attributes'=>array(
	            'empty'=>'请选择',
			),
		),
        'title'=>array(
            'type'=>'text',
            ),
        'name'=>array(
            'type'=>'text',
            'hint'=>'英文单词或者拼音串，用于构成友好URL地址，同类别页面不可重复',
            ),
       'content'=>array(
         	'type'=>'textarea',
         	'attributes'=>array('rows'=>30, 'class'=>'ckeditor'),
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