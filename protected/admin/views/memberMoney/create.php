<?php
$label = CActiveRecord::model('MemberMoney')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '我要充值',
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'我要充值', 'url'=>array('create'), 'active'=>true),
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
        'action'=>array('create'),
        'id'=>'edit-form',
        'method'=>'post',
        'enableAjaxValidation' => true, 
)); ?>

  <table class="tb tb2 nobdb">
    <tbody>

    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'phone') ?></td>
      <td class="td24"><?php echo $form->textField($model,'phone'); ?></td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'phone'); ?></td>
    </tr>

    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'money'); ?></td>
      <td class="td24"><?php echo $form->textField($model,'money'); ?></td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'money'); ?></td>
    </tr>

    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'password'); ?></td>
      <td class="td24"><?php echo $form->textField($model,'password'); ?></td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'password'); ?></td>
    </tr>    
<tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'content'); ?></td>
      <td class="td24"><?php echo $form->textField($model,'content'); ?></td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'content'); ?></td>
    </tr>

    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'verifyCode'); ?></td>
      <td class="td24"><?php echo $form->textField($model,'verifyCode'); ?>
      </td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'verifyCode'); ?></td>
    </tr>
    <tr class="hover" >
      <td class="td25"></td>
      <td class="td24">
<?php $this -> widget('system.web.widgets.captcha.CCaptcha', array('buttonLabel' => '看不清换一个', 'clickableImage' => true, 'imageOptions' => array('style' => 'cursor:pointer', 'align' => 'absmiddle','id'=>'register_verifycode_image')));?>
      </td>
      <td class="rowform" style="width:auto;"></td>
    </tr>
    </tbody>
    <tfooter>
    <tr>
      <th class="partition"></th>
      <th class="partition" colspan="2">
        <input type="submit" class="btn" id="submit_listsubmit" name="listsubmit" title="按 Enter 键可随时提交您的修改" value="提交" />
      </th>
    </tr>
    </tfooter>
  </table>

<?php $this->endWidget(); ?>