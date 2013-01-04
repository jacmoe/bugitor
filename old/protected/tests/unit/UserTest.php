<?php

class UserTest extends CDbTestCase {

    public $fixtures = array(
        'users' => 'User',
    );

    public function testRead() {
        echo "\nTesting reading User...";
        $retrievedUser = $this->users('usser');
        $this->assertTrue($retrievedUser instanceof User);
        echo "\nUsername retrieved: " . $retrievedUser->username;
        $this->assertEquals('Usser', $retrievedUser->username);
    }
}
