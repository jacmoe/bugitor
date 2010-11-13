<?php
/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return array(
	'sourcePath'=>'C:/wamp/www/protected',
	'messagePath'=>'C:/wamp/www/protected/messages',
	'languages'=>array('da'),
	'fileTypes'=>array('php'),
	'exclude'=>array(
		'/yiic.php',
		'/messages',
		'/vendors',
		//'/extensions',
		'/modules',
		//'/components',
		'/runtime',
		//'/extensions/simpleWorkflow',
	),
);