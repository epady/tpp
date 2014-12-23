<?php
echo "<?php\n";
echo "\$label = CActiveRecord::model('$this->modelClass')->modelName;\n";
?>

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
        <li>您可在此新增、删除、管理网站的<?php echo CActiveRecord::model($this->modelClass)->modelName ?>。</li>
      </ul></td>
  </tr>
</table>

<?php echo "<?php \$this->renderPartial('_search',array('model'=>\$model)); ?>\n";?>

<!-- search-form -->



<?php echo "<?php"; ?> $this->widget('GridView', array(
    'id'=>'<?php echo $this->class2id($modelClass); ?>-grid',
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
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
        if(++$count==7)
                echo "\t\t\t/*\n";

        if($this->checkExists($column->name, $this->image_fields))
          echo "\t\t\t'".$column->name.":image',\n";
        elseif($this->checkExists($column->name, $this->url_fields))
          echo "\t\t\t'".$column->name.":url',\n";
        elseif(strpos($column->name, 'is_') !== false)
          echo "\t\t\t'".$column->name.":boolean',\n";
        else
          echo "\t\t\t'".$column->name."',\n";
}
if($count>=7)
        echo "\t\t\t*/\n";
?>
        array(
            'header' => '操作',
            'class'=>'ButtonColumn',
        ),
    ),
)); ?>


<?php echo "<?php"; ?> $form=$this->beginWidget('CActiveForm', array(
        'action'=>array('operate'),
        'id'=>'operateForm',
        'method'=>'post',
        'htmlOptions' => array('id'=>'<?php echo $this->class2id($modelClass); ?>-operation')
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
  
<?php echo "<?php"; ?> $this->endWidget(); ?>
