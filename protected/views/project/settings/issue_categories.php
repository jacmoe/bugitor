<h3>Issue Categories</h3>
<?php if (!empty($model)) : ?>
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
      <?php echo CHtml::link('Update',array('issueCategory/update','id'=>$category->id, 'identifier' => $_GET['identifier'])); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>array('/issueCategory/delete', 'id' => $category->id, 'identifier' => $_GET['identifier']),
      	  'confirm'=>"Are you sure you want to delete {$category->name}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo 'No data to display'; ?></p>
<?php endif; ?>
<?php echo CHtml::link('New Category',array('issueCategory/create','identifier'=>$_GET['identifier'])); ?>
