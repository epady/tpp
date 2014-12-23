
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<table class="tb tb2 nobdt search_header">
    <tr>
      <th colspan="15" class="partition">搜索符合条件的后台用户</th>
    </tr>
    <tr>
      <td><table class="noborder">
		<tr>
		<td style="text-align:right;vertical-align:middle;">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id', array('size'=>5)); ?>
	
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>10,'maxlength'=>128)); ?>
	
		<?php echo $form->label($model,'role'); ?>
		<?php echo $form->dropdownlist($model,'role',Administrator::roleList(),array('empty'=>'全部角色')); ?>
		</td>

		<td>
		<?php echo CHtml::submitButton('搜索', array('class'=>'btn')); ?>
		</td>
</td>
</tr>
</table>
        <style>
            .noborder td{border:none;vertical-align:middle;}
            </style>
</table>

<?php $this->endWidget(); ?>
