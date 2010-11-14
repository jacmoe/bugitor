<h3>Versions</h3>
<?php if (!empty($model)) : ?>
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
      <?php echo CHtml::link('Update',array('version/update','id'=>$version->id, 'identifier' => $_GET['identifier'])); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>array('/version/delete', 'id' => $version->id, 'identifier' => $_GET['identifier']),
      	  'confirm'=>"Are you sure you want to delete {$version->name}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo 'No data to display'; ?></p>
<?php endif; ?>
<?php echo CHtml::link('New Version',array('version/create','identifier'=>$_GET['identifier'])); ?>
