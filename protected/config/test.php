<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/local.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'db'=>array(
				'connectionString' => 'mysql:host=localhost;dbname=ogitorbugs_test',
				'emulatePrepare' => true,
				'username' => 'superadmin',
							'tablePrefix' => 'bug_',
				'password' => 'jake2383',
				'charset' => 'utf8',
			),
		),
	)
);
