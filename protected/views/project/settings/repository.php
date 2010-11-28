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
<h3>Repositories</h3>
<?php if(Yii::app()->user->getState('pid') !== 'none') : ?>
<?php $actionUrl = $this->createUrl('project/waitforclone'); ?>
<?php Yii::app()->clientScript->registerScript('cloneSpinner',<<<EOD
var lpOnComplete = function(response) {
        if(response == '0'){
            $('#cloneSpinnerSuccess').show();
            $('#cloneSpinnerId').hide();
            $('#outputId').append('success');
        } else {
            // do more processing
            $('#outputId').append('.');
            lpStart();
        }
};

var lpStart = function() {
        $('#cloneSpinnerId').show();
	$.post('$actionUrl', {}, lpOnComplete, 'json');
};

$(document).ready(lpStart);
EOD
,CClientScript::POS_HEAD); ?>
<div id="cloneSpinnerId" style="display: none;">
<div class="flash-notice" align="center"><img src="/themes/classic/images/loading_1.gif" alt="Cloning Repository, please wait"><br/>
    Repository is being cloned.<br/><div id="outputId">This might take a while...</div><br/></div>
</div>
<div id="cloneSpinnerSuccess" style="display: none;">
<div class="flash-success" align="center">Repository successfully cloned!<br/></div>
</div>
<?php endif; ?>
<?php if (!empty($model)) : ?>
<table class="list" width="60%">
  <thead width="60">
  <tr>
      <td colspan="5">
      Project Repositories
      </td>
  </tr>

  <tr>
    <th width="20">Name</th>
    <th width="20">URL</th>
    <th width="20">Local Path</th>
    <th width="20">Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($model as $n=>$repository): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td width="20"><?php echo CHtml::encode($repository->name); ?></td>
    <td width="20"><?php echo CHtml::encode($repository->url); ?></td>
    <td width="20"><?php echo CHtml::encode($repository->local_path); ?></td>
    <td width="20">
      <?php echo CHtml::link('Update',array('repository/update','id'=>$repository->id, 'identifier' => $_GET['identifier'])); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>array('/repository/delete', 'id' => $repository->id, 'identifier' => $_GET['identifier']),
      	  'confirm'=>"Are you sure you want to delete {$repository->name}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo 'No data to display'; ?></p>
<?php endif; ?>
<?php echo CHtml::link('Add Repository',array('repository/create','identifier'=>$_GET['identifier'])); ?>
