<?php
$this->breadcrumbs=array(
	'综合设置'
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
		首页标题（SEO）
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textField('config[index_title]', Yii::app()->config->get('index_title'), array('size'=>40)); ?>
        </td>
    </tr>
	
    <tr>
        <th class="paddingT15">
		网站关键词（SEO）
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textArea('config[index_keywords]', Yii::app()->config->get('index_keywords'), array('rows'=>3, 'cols'=>50)); ?>
        </td>
    </tr>
    
    <tr>
        <th class="paddingT15">
		网站描述（SEO）
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textArea('config[index_description]', Yii::app()->config->get('index_description'), array('rows'=>3, 'cols'=>50)); ?>
        </td>
    </tr>
    <tr>
        <th class="paddingT15">
		访问统计代码
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textArea('config[stats_code]', Yii::app()->config->get('stats_code'), array('rows'=>3, 'cols'=>50)); ?>
        </td>
    </tr>
    <tr>
        <th class="paddingT15">
		网站底部信息
        </th>
        <td class="paddingT15 wordSpacing5">
		 <?php echo CHtml::textArea('config[footer_info]', Yii::app()->config->get('footer_info'), array('rows'=>10, 'cols'=>100)); ?>
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