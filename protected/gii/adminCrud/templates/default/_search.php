<table class="tb tb2 nobdt search_header">
    <tr>
        <th colspan="15" class="partition">搜索符合条件的数据</th>
    </tr>
    <tr>
        <td>
      


<?php echo "<?php"; ?> $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions' => array('class'=>'pure-form search-pannel'),
)); ?>

<table class="noborder">
<?php 
$count=0;
foreach($this->tableSchema->columns as $column): ?>
<?php
$field=$this->generateInputField($this->modelClass,$column);
if(strpos($field,'password')!==false)
{
	continue;
}
if(++$count==5) echo "<!--\n";
?>
<td style="text-align:right;vertical-align:middle;">
	<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
</td>
<td>
<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
</td>
<?php 
endforeach;

if($count>=5)
{
	echo "-->\n";
}

?>
        <td>
        <?php echo CHtml::submitButton('搜索', array('class'=>'btn')); ?>
        </td>
    </tr>
</table>
<?php echo "<?php"; ?> $this->endWidget(); ?>    
        </td>
    </tr>
</table>

