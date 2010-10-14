<?php	
	return array(
		'initial' => 'new',
		'node' => array(
			array('id'=>'new',	'transition'=>'assigned'),
			array('id'=>'assigned',	 'transition'=>'closed, rejected'),
			array('id'=>'closed', 'transition'=>'assigned'),
		)
	)
