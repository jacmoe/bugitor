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
 * Copyright (C) 2009 - 2010 Bugitor Team
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
<h3>Members</h3>
<?php if (!empty($model)) : ?>
<table class="list" width="60%">
  <thead width="60">
  <tr>
      <td colspan="3">
      Project Members
      </td>
  </tr>

  <tr>
    <th width="20">Username</th>
    <th width="20">Role</th>
    <th width="20">Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($model as $n=>$member): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td width="20"><?php echo CHtml::encode(ucfirst($member->user->username)); ?></td>
    <td width="20">
    <?php echo $member->role; ?>
    </td>
    <td width="20">
      <?php echo CHtml::link('Update',array('member/update','id'=>$member->id, 'identifier' => $_GET['identifier'])); ?>
      <?php echo CHtml::linkButton('Remove',array(
      	  'submit'=>array('member/delete', 'id' => $member->id, 'identifier' => $_GET['identifier']),
      	  'confirm'=>"Are you sure you want to delete {$member->user->username}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo 'No data to display'; ?></p>
<?php endif; ?>
<?php echo CHtml::link('Add Member',array('member/create','identifier'=>$_GET['identifier'])); ?>
