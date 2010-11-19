<?php
$this->pageTitle = $model->name . ' - Overview - ' . Yii::app()->name;
?>
<h3 class="overview">Overview</h3>
<div class="splitcontentleft">
<div class="project box">
<?php $this->widget('ProjectBox', array('project' => $model)) ?>
</div>
<div class="issues box">
<h3>Issues</h3>
<?php $this->widget('ProjectIssuesByTracker', array('project' => $model)) ?>
</div>
</div>
<div class="splitcontentright">
<div class="members box">
<h3>Members</h3>
Members here
</div>
<div class="activity box">
<h3>Recent Activity</h3>
Recent actitity here
</div>
</div>
