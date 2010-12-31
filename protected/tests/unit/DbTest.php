<?php
class DbTest extends CTestCase
{
	public function testDbConnection() {
		echo 'Testing Db connection...';
		$this->assertNotEquals(NULL, Yii::app()->db);	
	}
}
