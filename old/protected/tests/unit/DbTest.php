<?php
class DbTest extends CTestCase
{
	public function testDbConnection() {
		print "Testing Db connection...";
		$this->assertNotEquals(NULL, Yii::app()->db);	
	}
}
