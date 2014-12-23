<?php
$this->breadcrumbs=array(
	'新注册赠送设置'
);
?>
<div class="info">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=> array('enctype'=>'multipart/form-data'),
	'clientOptions'=>array('validateOnSubmit'=>false, 'validateOnChange'=>false),
)); ?>

<table class="infoTable">
	<tr>
        <th class="paddingT15">
	   新用户注册赠送
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[register_price]', Yii::app()->config->get('register_price'), array('size'=>40)); ?>
        </td>
        <td>元</td>
    </tr>
	<tr>
        <th></th>
        <td class="ptb20">
		<?php echo CHtml::submitButton('保存', array('class'=>'btn')); ?>
        </td>
   </tr>
</table>

<?php $this->endWidget(); ?>
</div><!-- form -->