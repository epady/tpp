<?php
$this->breadcrumbs=array(
	'联系方式设置'
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
		联系人
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[contact_linkman]', Yii::app()->config->get('contact_linkman'), array('size'=>40)); ?>
        </td>
    </tr>
	
    <tr>
        <th class="paddingT15">
		联系电话：
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[contact_phone]', Yii::app()->config->get('contact_phone'), array('size'=>40)); ?>
        </td>
    </tr>
    
    <tr>
        <th class="paddingT15">
        联系地址：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[contact_address]', Yii::app()->config->get('contact_address'), array('size'=>80)); ?>
        </td>
    </tr>
    

    <tr>
        <th class="paddingT15">
        手机号：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[contact_tel]', Yii::app()->config->get('contact_tel'), array('size'=>40)); ?>
        </td>
    </tr>
    
    
    <tr>
        <th class="paddingT15">
        传真：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[contact_fax]', Yii::app()->config->get('contact_fax'), array('size'=>40)); ?>
        </td>
    </tr>

    
    <tr>
        <th class="paddingT15">
        网址：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[contact_web]', Yii::app()->config->get('contact_web'), array('size'=>40)); ?>
        </td>
    </tr>
               

    <tr>
        <th class="paddingT15">
        邮箱：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[contact_mail]', Yii::app()->config->get('contact_mail'), array('size'=>40)); ?>
        </td>
    </tr>    


    <tr>
        <th class="paddingT15">
        微信公众账号：
        </th>
        <td class="paddingT15 wordSpacing5">
         <?php echo CHtml::textField('config[contact_weixin]', Yii::app()->config->get('contact_weixin'), array('size'=>40)); ?>
        </td>
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