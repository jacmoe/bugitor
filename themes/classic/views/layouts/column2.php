<?php $this->beginContent('//layouts/main'); ?>
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
                        $this->widget('DropDownRedirect', array(
                            'data' => array('<Switch Project>' => '<Switch Project>' ,'Bugitor' => 'Bugitor', 'MyProject' => 'MyProject'), // data od my dropdownlist
                            'url' => $this->createUrl($this->route, array_merge($_GET, array('name' => '__value__'))),//'/projects/'.'__value__'), // the url (__value__ will be replaced by the selected value)
                            'select' => '<Switch Project>', //the preselected value
                            'htmlOptions'=>array('class'=>'operations')
                            ));
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget();
		?>
		</div><!-- sidebar -->
	</div>
<?php $this->endContent(); ?>