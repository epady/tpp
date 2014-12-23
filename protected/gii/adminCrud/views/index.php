<?php
$class = get_class($model);
Yii::app()->clientScript->registerScript('gii.crud', "
$('#{$class}_controller').change(function(){
	$(this).data('changed', $(this).val() != '');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller = $('#{$class}_controller');
	if (!controller.data('changed'))
	{
		var id = new String($(this).val().match(/\\w*$/));
		if (id.length > 0)
		{
			id = id.substring(0, 1).toLowerCase() + id.substring(1);
		}
		controller.val(id);
	}
});
");
?>
<h1>后台CURD功能生成器</h1>
<p>定制的后台CURD生成器。更符合现在操作和风格。后续只需修改更少的代码即可完成相关功能。</p>
<?php $form = $this->beginWidget('CCodeForm', array(
	'model' => $model,
)); ?>
	<div class="row">
		<?php echo $form->labelEx($model, 'model'); ?>
		<?php echo $form->textField($model, 'model', array('size' => 65)); ?>
		<div class="tooltip">
			Model class is case-sensitive. It can be either a class name (e.g. <code>Post</code>)
			or the path alias of the class file (e.g. <code>application.models.Post</code>).
			Note that if the former, the class must be auto-loadable.
		</div>
		<?php echo $form->error($model, 'model'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'controller'); ?>
		<?php echo $form->textField($model, 'controller', array('size' => 65)); ?>
		<div class="tooltip">
			Controller ID is case-sensitive. CRUD controllers are often named after
			the model class name that they are dealing with. Below are some examples:
			<ul>
				<li><code>post</code> generates <code>PostController.php</code></li>
				<li><code>postTag</code> generates <code>PostTagController.php</code></li>
				<li><code>admin/user</code> generates <code>admin/UserController.php</code>.
					If the application has an <code>admin</code> module enabled,
					it will generate <code>UserController</code> (and other CRUD code)
					within the module instead.
				</li>
			</ul>
		</div>
		<?php echo $form->error($model, 'controller'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model, 'baseControllerClass'); ?>
		<?php echo $form->textField($model, 'baseControllerClass', array('size' => 65)); ?>
		<div class="tooltip">
			This is the class that the new CRUD controller class will extend from.
			Please make sure the class exists and can be autoloaded.
		</div>
		<?php echo $form->error($model, 'baseControllerClass'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'generateComponents'); ?>
		<?php echo $form->checkBox($model, 'generateComponents'); ?>
		<div class="tooltip">
			Whether additional components and widgets that override default one should be generated.
		</div>
		<?php echo $form->error($model, 'generateComponents'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model, 'layoutPrefix'); ?>
		<?php echo $form->textField($model, 'layoutPrefix', array('size' => 65)); ?>
		<div class="tooltip">
			This refers to the directory that the new layout file should be generated under.
			It should be specified in the form of a path alias relatively to the base layouts directory, for example, <code>admin.ext</code>.
		</div>
		<?php echo $form->error($model, 'layoutPrefix'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model, 'generateLayouts'); ?>
		<?php echo $form->checkBox($model, 'generateLayouts'); ?>
		<div class="tooltip">
			Whether layouts should be generated.
		</div>
		<?php echo $form->error($model, 'generateLayouts'); ?>
	</div>
<?php $this->endWidget(); ?>
