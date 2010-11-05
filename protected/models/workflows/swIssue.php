<?php	
	return array(
		'initial' => 'new',
		'node' => array(
			array('id'=>'new', 'label' => 'New',	'transition'=>'assigned'),
			array('id'=>'resolved',	 'label' => 'Resolved', 'transition'=>'assigned'),
			array('id'=>'rejected',	 'label' => 'Rejected', 'transition'=>'assigned'),
			array('id'=>'assigned',	 'label' => 'Assigned', 'transition'=>'resolved,rejected'),
		)
	);
