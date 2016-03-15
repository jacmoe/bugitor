<?php
namespace tests\codeception\unit\models;

use yii\codeception\DbTestCase;
use app\models\Project;
use tests\codeception\unit\fixtures\ProjectFixture;

class ProjectTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'project' => ProjectFixture::className(),
        ];
    }

    public function testCreate()
    {
        $project = new Project();
        $project->name = 'testproject';
        $this->assertTrue($project->validate(), 'Project should validate');
        //$this->assertTrue($project->save(), 'Project should save');
    }

}
