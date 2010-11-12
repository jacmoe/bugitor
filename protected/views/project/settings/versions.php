<?php
$this->pageTitle = $name . ' - Settings - Versions';
?>
<h3>Versions</h3>
<table class="list" width="60%">
  <thead width="60">
  <tr>
      <td colspan="4">
      Project Versions
      </td>
  </tr>

  <tr>
    <th width="20">Version</th>
    <th width="20">Description</th>
    <th width="20">Due Date</th>
    <th width="20">Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($model as $n=>$version): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td width="20"><?php echo CHtml::encode($version->name); ?></td>
    <td width="20"><?php echo CHtml::encode($version->description); ?></td>
    <td width="20"><?php echo CHtml::encode($version->effective_date); ?></td>
    <td width="20">
      <?php echo CHtml::link('Update',array('update','id'=>$version->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$version->id),
      	  'confirm'=>"Are you sure to delete #{$version->id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
