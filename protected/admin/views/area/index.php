<?php
$label = CActiveRecord::model('Area')->modelName;

$this->breadcrumbs=array(
        $label.'管理',
);

$this->menu=array(
        array('label'=>'管理'.$label, 'url'=>array('index'), 'active'=>true),
        array('label'=>'新增'.$label, 'url'=>array('create')),
);
?>

<table class="tb tb2 " id="tips">
  <tr>
    <th  class="partition">技巧提示</th>
  </tr>
  <tr >
    <td class="tipsblock" ><ul id="tipslis">
        <li>您可在此新增、删除、管理网站的地区。</li>
      </ul></td>
  </tr>
</table>

<?php $this->renderPartial('_search',array('model'=>$model)); ?>

<!-- search-form -->



<?php $this->widget('GridView', array(
    'id'=>'-grid',
    'dataProvider'=>$model->search(),
    'selectableRows' => 2,
    'itemsCssClass' => 'tb2',
    'rowCssClass' => array('hover odd', 'hover even'),
    'pager' => array('class'=>'CombPager'),
    //'template' => '{items} <div class="pure-g"><div class="pure-u-2-3">{pager}</div><div class="pure-u-1-3">{summary}</div></div>',
    'cssFile'=>false,
    'columns'=>array(
        array(
                'class'=>'CCheckBoxColumn',
                'name'=>'id',
                'id' => 'id',
                'headerHtmlOptions' => array('style'=>'width:40px'),
                'checkBoxHtmlOptions'=>array('class'=>'checkbox'),
            ),
			'id',
			'parent:name:上级分类',
			'name',
			'is_open:boolean',
			'sort_order',
        array(
            'header' => '操作',
            'class'=>'ButtonColumn',
        ),
    ),
)); ?>


<?php $form=$this->beginWidget('CActiveForm', array(
        'action'=>array('operate'),
        'id'=>'operateForm',
        'method'=>'post',
        'htmlOptions' => array('id'=>'-operation')
)); ?>
  <table class="tb tb2 nobdb">
    <thead>
    <tr>
      <th class="partition"></th>
      <th class="partition">操作</th>
      <th class="partition">选项</th>
    </tr>
    </thead>
    <tbody>
    <tr class="hover" >
      <td class="td25"><input class="radio" type="radio" name="operation" value="delete"></td>
      <td class="td24">批量删除</td>
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
