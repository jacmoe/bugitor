<?php

class ProjectTest extends CDbTestCase {

    public $fixtures = array(
        'projects' => 'Project',
    );

    public function testCreate() {
        echo "\nTesting creating Project...";
        $newProject = new Project;
        $newProjectName = 'Test Project Creation';
        $newProject->setAttributes(array(
            'name' => $newProjectName,
            'description' => 'fuckoff',
            'created' => '2009-09-09 00:00:00',
            'updated' => '2009-09-09 00:00:00',
            'identifier' => 'testProject',
        ));
        $this->assertTrue($newProject->save(false));
        $retrievedProject = Project::model()->findByPk($newProject->id);
        $this->assertTrue($retrievedProject instanceof Project);
        $this->assertEquals($newProjectName, $retrievedProject->name);
    }

    public function testRead() {
        echo "\nTesting reading Project...";
        $retrievedProject = $this->projects('project1');
        $this->assertTrue($retrievedProject instanceof Project);
        $this->assertEquals('Test Project 1', $retrievedProject->name);
    }

    public function testUpdate() {
        echo "\nTesting updating Project...";
        $project = $this->projects('project2');
        $updatedProjectName = 'Updated Test Project 2';
        $project->name = $updatedProjectName;
        $this->assertTrue($project->save(false));
//read back the record again to ensure the update worked
        $updatedProject = Project::model()->findByPk($project->id);
        $this->assertTrue($updatedProject instanceof Project);
        $this->assertEquals($updatedProjectName, $updatedProject->name);
    }

    public function testDelete() {
        echo "\nTesting deleting Project...";
        $project = $this->projects('project2');
        $savedProjectId = $project->id;
        $this->assertTrue($project->delete());
        $deletedProject = Project::model()->findByPk($savedProjectId);
        $this->assertEquals(NULL, $deletedProject);
    }

}