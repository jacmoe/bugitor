<?php
$this->pageTitle = $model->name . ' - Overview - ' . Yii::app()->name;
?>
<h3 class="overview">Overview</h3>
<div class="splitcontentleft">
<div class="project box">
<h2><?php echo $model->name; ?></h2>
<?php echo $model->getDescription(); ?>
<?php if($model->homepage != ''): ?>
Homepage: <?php echo CHtml::link($model->homepage); ?>
<?php endif; ?>
<br/>
<br/>
<div class="alt" style="font-size:smaller;">Created : <?php echo Time::timeAgoInWords($model->created); ?></div>
</div>
<div class="issues box">
<h3>Issues</h3>
<?php echo CHtml::link(Yii::t('Bugitor','Bugs'), array('issue/index', 'identifier' => $model->identifier, 'Issue[tracker_id]' => 'Bug')); ?>: <?php echo $model->issueOpenBugCount . ' ' . Yii::t('Bugitor','open'); ?> / <?php echo $model->issueBugCount; ?><br/>
<?php echo CHtml::link(Yii::t('Bugitor','Features'), array('issue/index', 'identifier' => $model->identifier, 'Issue[tracker_id]' => 'Feature')); ?>: <?php echo $model->issueOpenFeatureCount . ' ' . Yii::t('Bugitor','open'); ?> / <?php echo $model->issueFeatureCount; ?><br/>
<br/>
<?php echo CHtml::link(Yii::t('Bugitor','View all issues'), array('issue/index', 'identifier' => $model->identifier)); ?>
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
