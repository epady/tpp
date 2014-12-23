<?php
$label = CActiveRecord::model('Order')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '修改'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'修改'.$label, 'url'=>array('update','id'=>$model->id), 'active'=>true),
	array('label'=>'查看'.$label, 'url'=>array('view','id'=>$model->id)),
);
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/js/jquery.artZoom.js');
Yii::app()->clientScript->registerScriptFile('/uploadify/jquery.uploadify-3.1.js');

Yii::app()->clientScript->registerScriptFile('/Jcrop/js/jquery.Jcrop.js');
Yii::app()->clientScript->registerCssFile('/js/jquery.artZoom.css');
Yii::app()->clientScript->registerCssFile('/Jcrop/css/jquery.Jcrop.css');
Yii::app()->clientScript->registerCssFile('/uploadify/uploadify.css');

?>
<table class="tb tb2 " id="tips">
  <tr>
    <th  class="partition">技巧提示</th>
  </tr>
  <tr >
    <td class="tipsblock" ><ul id="tipslis">
        <li>添加，请完整填写各类信息。</li>
      </ul></td>
  </tr>
</table>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'edit-form',
        'method'=>'post',
        'htmlOptions' => array('enctype'=>'multipart/form-data')
)); ?>

  <table class="tb tb2 nobdb">
    <tbody>

    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'status') ?></td>
      <td class="td24"><?php echo $form->radioButtonList($model,'status',array('2'=>'订单异常','4'=>'订单完成')); ?></td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'status'); ?></td>
    </tr>


    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'reimage') ?></td>
      <td class="td24">
      <?php echo $form->fileField($model,'reimage',array('id'=>'img')); ?>
      <!--input type="text" id="avatarUpload" value="" />
      <input type="hidden" id="x" name="x" />
      <input type="hidden" id="y" name="y" />
      <input type="hidden" id="w" name="w" />
      <input type="hidden" id="h" name="h" />
      <div style="padding-top:-10px;">
        <a href="javascript:$('#avatarUpload').uploadify('upload','*')">开始上传</a> |
        <a href="javascript:$('#avatarUpload').uploadify('cancel', '*')">取消上传</a>
      </div>
      <div class="row imgchoose" style="display:none;">编辑头像：<br /><img src="" id="target" /></div-->
      </td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'reimage'); ?></td>
    </tr>


    <tr class="hover" >
      <td class="td25"><?php echo $form->label($model,'receipt') ?></td>
      <td class="td24"><?php echo $form->textarea($model,'receipt',array('style'=>'height:80px;width:500px;')); ?></td>
      <td class="rowform" style="width:auto;"><?php echo $form->error($model,'receipt'); ?></td>
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

  <table class="tb tb2 nobdb">
    <tr>
      <th colspan="15" class="partition">详细资料</th>
    </tr>
    </table>

<table class="tb tb2 nobdb">
<tbody>

<tr class="hover odd"><td class="td21">ID：</td><td><?php echo $model->id; ?></td></tr>
<tr class="hover odd"><td class="td21">会员ID：</td><td><?php echo $model->member_id; ?></td></tr>
<tr class="hover odd"><td class="td21">会员手机号：</td><td><?php echo $model->member->phone; ?></td></tr>
<tr class="hover odd"><td class="td21">SN：</td><td><?php echo $model->sn; ?></td></tr>
<tr class="hover odd"><td class="td21">状态：</td><td><?php echo $model->statusStr; ?></td></tr>
<tr class="hover odd"><td class="td21">营运人：</td><td style="color:red;"><?php echo $model->service; ?></td></tr>
<tr class="hover odd"><td class="td21">皮重：</td><td style="color:red;"><?php echo $model->gw; ?></td></tr>
<tr class="hover odd"><td class="td21">是否：</td><td><?php echo $model->is_diff ? '是' : '否'; ?></td></tr>
<tr class="hover odd"><td class="td21">描述：</td><td><?php echo $model->content; ?></td></tr>
<tr class="hover odd"><td class="td21">回执：</td><td><?php echo $model->receipt; ?></td></tr>
<tr class="hover odd"><td class="td21">添加时间：</td><td><?php echo date('Y-m-d H:i:s',$model->created); ?></td></tr>
<tr class="hover odd"><td class="td21">修改时间：</td><td><?php echo date('Y-m-d H:i:s',$model->updated); ?></td></tr>
<?php if($model->image): ?>
<tr class="hover odd"><td class="td21">订单图片：</td><td><?php echo CHtml::image($model->image,'',array('class'=>'artZoom','width'=>'300')); ?></td></tr>
<?php endif; ?>
<?php if($model->reimage): ?>
<tr class="hover odd"><td class="td21">回执图片：</td><td><?php echo CHtml::image($model->reimage,'',array('class'=>'artZoom','width'=>'300')); ?></td></tr>
<?php endif; ?>

</tbody></table>



<script>
jQuery(function ($) {
  $('.artZoom').artZoom({
    path: '/js',  // 设置artZoom图片文件夹路径
    preload: true,    // 设置是否提前缓存视野内的大图片
    blur: true,     // 设置加载大图是否有模糊变清晰的效果
    
    // 语言设置
    left: '左旋转',    // 左旋转按钮文字
    right: '右旋转',   // 右旋转按钮文字
    source: '看原图'   // 查看原图按钮文字
  });
});

</script>
