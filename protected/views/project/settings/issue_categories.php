<?php
$this->pageTitle = $name . ' - Settings - Issue Categories';
?>
<h3>Issue Categories</h3>
<table class="list" width="60%">
  <thead width="60">
  <tr>
      <td colspan="3">
      Project Issue Categories
      </td>
  </tr>

  <tr>
    <th width="10">Category</th>
    <th width="20">Description</th>
    <th width="10">Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($model as $n=>$category): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td width="20"><?php echo CHtml::encode($category->name); ?></td>
    <td width="20"><?php echo CHtml::encode($category->description); ?></td>
    <td width="20">
      <?php echo CHtml::link('Update',array('update','id'=>$category->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$category->id),
      	  'confirm'=>"Are you sure to delete #{$category->id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
