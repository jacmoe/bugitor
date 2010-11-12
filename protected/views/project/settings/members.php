<?php
$this->pageTitle = $name . ' - Settings - Members';
?>
<h3>Members</h3>
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
      <?php echo CHtml::link('Update',array('update','id'=>$member->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$member->id),
      	  'confirm'=>"Are you sure to delete #{$member->id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
