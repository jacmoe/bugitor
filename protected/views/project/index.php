<?php
$this->pageTitle=Yii::app()->name . ' - Projects';
?>
<h3 class="projects">Projects</h3>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view'
)); ?>
