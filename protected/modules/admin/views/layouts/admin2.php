<?php $this->beginContent('//layouts/admin'); ?>
<div class="span-19">
    <div id="content">
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<div class="span-5 last">
    <div id="sidebar">
        <?php
        if (((Yii::app()->controller->id === 'project')||(Yii::app()->controller->id === 'issue')) && (isset($_GET['name']))) {
            $this->widget('DropDownRedirect', array(
                'data' => Yii::app()->controller->getProjects(),
                'url' => $this->createUrl($this->route, array_merge($_GET, array('name' => '__value__'))),
                'select' => $_GET['name'], //the preselected value
                'htmlOptions' => array('class' => 'operations')
            ));
        }
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => 'Operations',
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items' => $this->menu,
            'htmlOptions' => array('class' => 'operations'),
        ));
        $this->endWidget();
        ?>
    </div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>