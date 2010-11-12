<?php
$this->pageTitle = $name . ' - Settings - Repositories';
?>
<h3>Repositories</h3>
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
      <?php echo CHtml::link('Update',array('update','id'=>$repository->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$repository->id),
      	  'confirm'=>"Are you sure to delete #{$repository->id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
