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
 * Copyright (C) 2009 - 2012 Bugitor Team
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
<h3>Milestones</h3>
<?php if (!empty($model)) : ?>
<table class="list" width="60%">
  <thead width="60">
  <tr>
      <td colspan="4">
      Project Milestones
      </td>
  </tr>

  <tr>
    <th width="20">Milestone</th>
    <th width="20">Title</th>
    <th width="20">Description</th>
    <th width="20">Due Date</th>
    <th width="20">Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($model as $n=>$milestone): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td width="20"><?php echo CHtml::encode($milestone->name); ?></td>
    <td width="20"><?php echo CHtml::encode($milestone->title); ?></td>
    <td width="20"><?php echo CHtml::encode($milestone->description); ?></td>
    <td width="20"><?php echo CHtml::encode($milestone->effective_date); ?></td>
    <td width="20">
      <?php echo CHtml::link('Update',array('milestone/update','id'=>$milestone->id, 'identifier' => $_GET['identifier'])); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>array('/milestone/delete', 'id' => $milestone->id, 'identifier' => $_GET['identifier']),
      	  'confirm'=>"Are you sure you want to delete {$milestone->name}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo 'No data to display'; ?></p>
<?php endif; ?>
<?php echo CHtml::link('New Milestone',array('milestone/create','identifier'=>$_GET['identifier'])); ?>
