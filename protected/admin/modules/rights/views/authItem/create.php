<?php $this->breadcrumbs = array(
	'权限管理',
	Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']))),
); ?>

<div class="createAuthItem">

	<h2><?php echo Rights::t('core', 'Create :type', array(
		':type'=>Rights::getAuthItemTypeName($_GET['type']),
	)); ?></h2>

	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>

</div>