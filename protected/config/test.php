<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/local.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
		),
	)
);
