<table class="tb tb2 nobdt search_header">
    <tr>
        <th colspan="15" class="partition">搜索符合条件的数据</th>
    </tr>
    <tr>
        <td>
      


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array('class'=>'pure-form search-pannel'),
)); ?>

<table class="noborder">
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'id'); ?>
</td>
<td>
<?php echo $form->textField($model, 'id', array('class' => 'text_field')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'type'); ?>
</td>
<td>
<?php echo $form->dropdownlist($model, 'type', array('订单管理'=>'订单管理'),array('empty' => '全部内容')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'operator_name'); ?>
</td>
<td>
<?php echo $form->textField($model, 'operator_name', array( 'maxlength' => 50, 'class' => '')); ?>
</td>
<!--
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'operator_id'); ?>
</td>
<td>
<?php echo $form->textField($model, 'operator_id', array('class' => 'text_field')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'operator_name'); ?>
</td>
<td>
<?php echo $form->textField($model, 'operator_name', array( 'maxlength' => 50, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'type'); ?>
</td>
<td>
<?php echo $form->textField($model, 'type', array( 'maxlength' => 50, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'category'); ?>
</td>
<td>
<?php echo $form->textField($model, 'category', array( 'maxlength' => 40, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'description'); ?>
</td>
<td>
<?php echo $form->textField($model, 'description', array( 'maxlength' => 255, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'is_delete'); ?>
</td>
<td>
<?php echo $form->textField($model, 'is_delete', array('class' => 'text_field')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'model'); ?>
</td>
<td>
<?php echo $form->textField($model, 'model', array( 'maxlength' => 50, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'model_pk'); ?>
</td>
<td>
<?php echo $form->textField($model, 'model_pk', array( 'maxlength' => 100, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'model_attributes_old'); ?>
</td>
<td>
<?php echo $form->textArea($model, 'model_attributes_old', array('rows' => 6, 'cols' => 50)); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'model_attributes_new'); ?>
</td>
<td>
<?php echo $form->textArea($model, 'model_attributes_new', array('rows' => 6, 'cols' => 50)); ?>
</td>
-->
        <td>
        <input class="btn" type="submit" name="yt0" value="搜索" />        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>    
        </td>
    </tr>
</table>

