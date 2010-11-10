<table class="dataGrid">
  <thead>
  <tr>
      <td colspan="9">
      <?php $this->widget('CDataFilterWidget',
                array(
                    'filters'=>$filters,
                    'ajaxMode'=>true, 'beforeUpdateCode'=>'showIndicator();',
                    //following parameters added just for demo purpose
                    //these parameters are default for this form
                    'formAction'=>'/issue/index',
                    'formMethod'=>'get', 'formOptions'=>array()
                ));
      ?>
      </td>
  </tr>
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th><?php echo $sort->link('priority_id'); ?></th>
    <th><?php echo $sort->link('subject'); ?></th>
    <th><?php echo $sort->link('user_id'); ?></th>
    <th><?php echo $sort->link('status'); ?></th>
    <th><?php echo $sort->link('closed'); ?></th>
    <th><?php echo $sort->link('project_id'); ?></th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('view','id'=>$model->id, 'name' => $model->project->name)); ?></td>
    <td><?php echo CHtml::encode($model->issuePriority->name); ?></td>
    <td><?php echo CHtml::encode($model->subject); ?></td>
    <td><?php echo CHtml::encode($model->user->username); ?></td>
    <td><?php echo CHtml::encode($model->status); ?></td>
    <td><?php echo CHtml::encode($model->closed); ?></td>
    <td><?php echo CHtml::encode($model->project->name); ?></td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<?php $this->widget('CustomLinkPager',array('pages'=>$pages)); ?>

<?php
$scriptInit = <<<EOD

$('#sort-buttons a, .yiiPager a').click(updatePage);

EOD;
echo CHtml::script($scriptInit);
?>
