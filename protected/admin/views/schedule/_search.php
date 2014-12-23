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
	<?php echo $form->label($model,'shop_id'); ?>
</td>
<td>
<?php echo $form->dropdownlist($model, 'shop_id', CHtml::listData(Shop::model()->findAll(),'id','name'), array('empty' => '全部摄影师')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'date'); ?>
</td>
<td>
<?php echo $form->textField($model, 'date', array('class' => 'text_field')); ?>
</td>
<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'user_id'); ?>
</td>
<td>
<?php echo $form->textField($model, 'user_id', array('class' => 'text_field')); ?>
</td>

<td style="text-align:right;vertical-align:middle;">
	<?php echo $form->label($model,'service_id'); ?>
</td>
<td>
<?php echo $form->dropdownlist($model, 'service_id',CHtml::listData(Service::model()->findAll(),'id','name'), array('empty' => '全部')); ?>
</td>

        <td>
        <input class="btn" type="submit" name="yt0" value="搜索" />        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>    
        </td>
    </tr>
</table>

