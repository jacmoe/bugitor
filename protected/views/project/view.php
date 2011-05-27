<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2011 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php
$this->pageTitle = $model->name . ' - Overview - ' . Yii::app()->name;
?>
<h3 class="overview">Overview</h3>
<!--<div class="contextual">
<?php //if(Yii::app()->user->checkAccess('Project.Delete')) echo CHtml::link('Delete Project', '#', array('submit' => array('delete','id' => $model->id), 'confirm' => 'Are you sure you want to delete this project? Cannot be undone!!', 'class' => 'icon icon-del')); ?>
</div>-->
<div class="splitcontentleft">
<div class="project box">
<?php $this->widget('ProjectBox', array('project' => $model)) ?>
</div>
<div class="roadmap box">
    <?php $version_limit = 2; ?>
    <?php $open_version_count = Version::getOpenVersionCount($model->id); ?>
    <?php if($open_version_count < 2) $version_limit = $open_version_count; ?>
    <h3>Roadmap <font style="font-style: italic; font-size: 0.5em;">(Showing <?php echo $version_limit; ?> <?php echo ((1 == $open_version_count) ? 'version' : 'versions') ?> out of <?php echo $open_version_count; ?> open)</font></h3>
<?php $this->widget('Roadmap', array('versions' => $model->versions, 'identifier' => $model->identifier)) ?>
</div>
<div class="members box">
<h3>Members</h3>
<?php $this->widget('ProjectMembers', array('project' => $model)) ?>
</div>
</div>
<div class="splitcontentright">
<div class="issues box">
<h3>Issues</h3>
<?php $this->widget('ProjectIssuesByTracker', array('project' => $model)) ?>
</div>
<div class="activity box">
<h3>Recent Activity</h3>
<?php $this->widget('ProjectActivity', array('projectId' => $model->id, 'displayLimit' => 5)); ?>
</div>
</div>
