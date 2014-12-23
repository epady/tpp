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
	<?php echo $form->label($model,'name'); ?>
</td>
<td>
<?php echo $form->textField($model, 'name', array( 'maxlength' => 60, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'phone'); ?>
</td>
<td>
<?php echo $form->textField($model, 'phone', array( 'maxlength' => 11, 'class' => '')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'email'); ?>
</td>
<td>
<?php echo $form->textField($model, 'email', array( 'maxlength' => 60, 'class' => '')); ?>
</td>

<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'city_id'); ?>
</td>
<td>
<?php echo $form->dropdownlist($model, 'city_id', Area::options(0),array('empty' => '全部')); ?>
</td>

<td>
<?php echo $form->dropdownlist($model, 'status', Shop::statusOptions(), array('empty' => '全部状态')); ?>
</td>
        <td>
        <input class="btn" type="submit" name="yt0" value="搜索" /> </td>
    </tr>
</table>
<?php $this->endWidget(); ?>    
        </td>
    </tr>
</table>

