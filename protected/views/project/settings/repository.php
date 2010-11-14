<h3>Repositories</h3>
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
    <th width="20">Login</th>
    <th width="20">Password</th>
    <th width="20">Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($model as $n=>$repository): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td width="20"><?php echo CHtml::encode($repository->name); ?></td>
    <td width="20"><?php echo CHtml::encode($repository->url); ?></td>
    <td width="20"><?php echo CHtml::encode($repository->login); ?></td>
    <td width="20"><?php echo CHtml::encode($repository->password); ?></td>
    <td width="20">
      <?php echo CHtml::link('Update',array('repository/update','id'=>$repository->id)); ?>
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
