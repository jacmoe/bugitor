<?php $this->breadcrumbs = array(
	'Rights'=>Rights::getBaseUrl(),
	Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']))),
); ?>

<div class="createAuthItem">

	<h2><?php echo Rights::t('core', 'Create :type', array(':type'=>Rights::getAuthItemTypeName($_GET['type']))); ?></h2>

	<div class="form">

		<?php echo $form->render(); ?>

	</div>

</div>