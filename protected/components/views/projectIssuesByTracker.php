<?php $project = $this->getProject(); ?>
<?php echo CHtml::link(Yii::t('Bugitor','Bugs'), array('issue/index', 'identifier' => $project->identifier, 'Issue[tracker_id]' => 'Bug')); ?>: <?php echo $project->issueOpenBugCount . ' ' . Yii::t('Bugitor','open'); ?> / <?php echo $project->issueBugCount; ?><br/>
<?php echo CHtml::link(Yii::t('Bugitor','Features'), array('issue/index', 'identifier' => $project->identifier, 'Issue[tracker_id]' => 'Feature')); ?>: <?php echo $project->issueOpenFeatureCount . ' ' . Yii::t('Bugitor','open'); ?> / <?php echo $project->issueFeatureCount; ?><br/>
<br/>
<?php echo CHtml::link(Yii::t('Bugitor','View all issues'), array('issue/index', 'identifier' => $project->identifier)); ?>
