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
    <td width="20"><?php echo CHtml::encode($member->username); ?></td>
    <td width="20">
    <?php $assignments = Rights::getAssignedRoles($member->id); ?>
    <?php foreach($assignments as $assignment) {
        echo $assignment->name . ' . ';
        } ?>
    </td>
    <td width="20">
      <?php echo CHtml::link('Update',array('updateUser','id'=>$member->id)); ?>
      <?php echo CHtml::linkButton('Remove',array(
      	  'submit'=>array('addUser', 'id' => $member->id, 'identifier' => $_GET['identifier']),
      	  'confirm'=>"Are you sure you want to delete {$member->username}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo 'No data to display'; ?></p>
<?php endif; ?>
<?php echo CHtml::link('Add Member',array('addUser','identifier'=>$_GET['identifier'])); ?>
