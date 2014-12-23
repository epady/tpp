<?php
$this->breadcrumbs=array(
	'短信设置'
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
	   短信签名
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[sms_sign]', Yii::app()->config->get('sms_sign'), array('size'=>40)); ?>
        </td>
        <td>只有通过签名认证，短信才以发送得以接收。</td>
    </tr>
	<tr>
        <th class="paddingT15">
	   短信账号
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[sms_user]', Yii::app()->config->get('sms_user'), array('size'=>40)); ?>
        </td>
        <td>动力思维的短信接口提供的账号密码</td>
    </tr>
	<tr>
        <th class="paddingT15">
	   短信密码
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[sms_pwd]', Yii::app()->config->get('sms_pwd'), array('size'=>40)); ?>
        </td>
        <td></td>
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