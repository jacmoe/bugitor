<?php
$this->pageTitle = $project_name . 'New Issue - ' . Yii::app()->name ;
?>
<h3 class="issues">New Issue</h3>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>