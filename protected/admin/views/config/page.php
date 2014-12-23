<?php
$this->breadcrumbs=array(
	'单页设置'
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
		单页类别设置
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textArea('config[page_category]', Yii::app()->config->get('page_category'), array('rows'=>9,'cols'=>50)); ?>
         <p>一行一条类别</p>
        </td>
    </tr>
	<tr>
        <th class="paddingT15">
		首页公司介绍
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textArea('config[page_companyinfo]', Yii::app()->config->get('page_companyinfo'), array('rows'=>9,'cols'=>50)); ?>
         <p>支持HTML语法</p>
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