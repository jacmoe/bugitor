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
 * Copyright (C) 2009 - 2013 Bugitor Team
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
?>
<h3>Settings</h3>
<div class="splitcontentleft">
<table class="dataGrid">
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('pagesize')); ?>
</th>
<td><?php echo CHtml::encode($model->pagesize); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('hg_executable')); ?>
</th>
<td><?php echo CHtml::encode($model->hg_executable); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('git_executable')); ?>
</th>
<td><?php echo CHtml::encode($model->git_executable); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('svn_executable')); ?>
</th>
<td><?php echo CHtml::encode($model->svn_executable); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('python_path')); ?>
</th>
<td><?php echo CHtml::encode($model->python_path); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('default_scm')); ?>
</th>
<td><?php echo CHtml::encode($model->default_scm); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('default_timezone')); ?>
</th>
<td><?php echo CHtml::encode($model->default_timezone); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('hostname')); ?>
</th>
<td><?php echo CHtml::encode($model->hostname); ?>
</td>
</tr>
<tr>
<th class="label">
<?php echo CHtml::encode($model->getAttributeLabel('logging_enabled')); ?>
</th>
<td><?php echo Yii::app()->format->boolean($model->logging_enabled); ?>
</td>
</tr>
</table>
<?php echo CHtml::link('Change Settings', array('update')); ?>
<br/>
<br/>
</div>
<div class="splitcontentright"></div>
