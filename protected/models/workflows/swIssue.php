<?php	
return array(
        'initial' => 'new',
        'node' => array(
            array('id' => 'new',
                'label' => 'New',
                'transition' => array(
                    'assigned' => '$this->sendAssignedNotice(true)',
                    'resolved' => '$this->markAsClosed()',
                    'rejected' => '$this->markAsClosed(true)',
                )
            ),
            array('id' => 'resolved',
                'label' => 'Resolved',
                'transition' => array(
                    'assigned' => '$this->sendAssignedNotice(true, true)',
                    'rejected',
                )
            ),
            array('id' => 'rejected',
                'label' => 'Rejected',
                'transition' => array(
                    'assigned' => '$this->sendAssignedNotice(true, true)',
                    'resolved',
                )
            ),
            array('id' => 'assigned',
                'label' => 'Assigned',
                'transition' => array(
                    'unassigned' => '$this->sendAssignedNotice(false)',
                    'resolved' => '$this->markAsClosed()',
                    'rejected' => '$this->markAsClosed(true)',
                )
            ),
            array('id' => 'unassigned',
                'label' => 'Unassigned',
                'transition' => array(
                    'assigned' => '$this->sendAssignedNotice(true)',
                    'resolved' => '$this->markAsClosed()',
                    'rejected' => '$this->markAsClosed(true)',
                )
            ),
        )
    );
