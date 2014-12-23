<?php 
echo "<?php 
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
	'elements'=>array(\n        "; ?>
<?php foreach($this->tableSchema->columns as $column) { if($column->isPrimaryKey || $column->name == 'created' || $column->name == 'updated') continue; ?>
'<?php echo $column->name ?>'=>array(
            'type'=>'<?php echo $input = $this->getFieldType($modelClass,$column); ?>',
<?php if($input == 'file') echo "\t\t\t'enableAjaxValidation'=>false,\n"; ?>
<?php if($input == 'textarea' && $this->checkExists($column->name, $this->editor_fields)) echo "\t\t\t'attributes'=>array('class'=>'ckeditor'),\n"; ?>
<?php if($input == 'ext.FileFieldWidget') echo "\t\t\t'enableAjaxValidation'=>false,\n\t\t\t'thumbOptions'=>array('fullimage'=>true, 'width'=>200, 'height'=>200),\n"; ?>
<?php if($input == 'zii.widgets.jui.CJuiDatePicker') echo "\t\t\t'htmlOptions'=>array('size'=>12),
            'language'=>'zh-CN',
			'options'=>array(
				//'minDate'=>'js:new Date()',
				'changeMonth'=>'js:true',
				'changeYear'=>'js:true',
				//'numberOfMonths'=>2,
			),\n"; ?>
        ),
        <?php } ?>
   	),      
	'buttons'=>array(
        'btnsubmit'=>array(
            'type'=>'submit',
            'label'=>'确定',
    		'class'=>'btn',
        ),
    ),
);  