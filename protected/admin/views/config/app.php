<?php
$this->breadcrumbs=array(
	'APP版本管理'
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
	   Android 当前版本号
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[app_version]', Yii::app()->config->get('app_version'), array('size'=>40)); ?>
        </td>
        <td></td>
    </tr>
	<tr>
        <th class="paddingT15">
	   Android 当前下载地址
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[app_fileurl]', Yii::app()->config->get('app_fileurl'), array('size'=>40)); ?>
        </td>
        <td>请写绝对网址： http://abc.com/1.apk</td>
    </tr>
    <tr>
        <th class="paddingT15">
       升级提示语：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[app_message]', Yii::app()->config->get('app_message'), array('size'=>40)); ?>
        </td>
        <td>提示用户升级的语句</td>
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