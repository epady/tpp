<?php
$label = CActiveRecord::model('Order')->modelName;

$this->breadcrumbs=array(
	$label.'管理',
    '查看'.$label,
);

$this->menu=array(
	array('label'=>'管理'.$label, 'url'=>array('index')),
	array('label'=>'查看'.$label, 'url'=>array('view','id'=>$model->id), 'active'=>true),
);
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile('/js/jquery.artZoom.js');
Yii::app()->clientScript->registerCssFile('/js/jquery.artZoom.css');
?>

<style>
.artZoom{padding:3px;background:#FFF;border:1px solid #EBEBEB;}
</style>

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