<?php

class ProjectTest extends CDbTestCase {

    public $fixtures = array(
        'projects' => 'Project',
    );

    public function testRead() {
        echo 'Testing reading from Project...';
        $retrievedProject = $this->projects('project1');
        $this->assertTrue($retrievedProject instanceof Project);
        $this->assertEquals('Test Project 1', $retrievedProject->name);
    }

}