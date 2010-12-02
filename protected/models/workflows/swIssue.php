<?php	
return array(
        'initial' => 'new',
        'node' => array(
            array('id' => 'new',
                'label' => 'New',
                'transition' => array(
                    'assigned',
                    'resolved' => '$this->markAsClosed()',
                    'rejected' => '$this->markAsClosed(true)',
                )
            ),
            array('id' => 'resolved',
                'label' => 'Resolved',
                'transition' => array(
                    'assigned' => '$this->reOpen()',
                    'rejected',
                )
            ),
            array('id' => 'rejected',
                'label' => 'Rejected',
                'transition' => array(
                    'assigned' => '$this->reOpen()',
                    'resolved',
                )
            ),
            array('id' => 'assigned',
                'label' => 'Assigned',
                'transition' => array(
                    'unassigned',
                    'resolved' => '$this->markAsClosed()',
                    'rejected' => '$this->markAsClosed(true)',
                )
            ),
            array('id' => 'unassigned',
                'label' => 'Unassigned',
                'transition' => array(
                    'assigned',
                    'resolved' => '$this->markAsClosed()',
                    'rejected' => '$this->markAsClosed(true)',
                )
            ),
        )
    );
