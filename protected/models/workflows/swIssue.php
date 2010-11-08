<?php	
	return array(
		'initial' => 'new',
		'node' => array(
			array('id' => 'new', 'label' => 'New', 'transition' => array('assigned' => '$this->sendAssignedNotice(true)')),
			array('id' => 'resolved',	 'label' => 'Resolved', 'transition' => 'assigned'),
			array('id' => 'rejected',	 'label' => 'Rejected', 'transition' => 'assigned'),
			array('id' => 'assigned',	 'label' => 'Assigned', 'transition' => array('unassigned' => '$this->sendAssignedNotice(false)','resolved','rejected')),
			array('id' => 'unassigned',	 'label' => 'Unassigned', 'transition' => array('assigned' => '$this->sendAssignedNotice(true)','resolved','rejected')),
		)
	);
