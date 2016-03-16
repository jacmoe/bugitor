<?php
namespace tests\codeception\unit\models;

use yii\codeception\DbTestCase;
use app\models\Project;
use tests\codeception\unit\fixtures\ProjectFixture;

class ProjectTest extends DbTestCase
{
    use \Codeception\Specify;

    public function fixtures()
    {
        return [
            'project' => ProjectFixture::className(),
        ];
    }

    private $project;

    public function testCreate()
    {
        $this->project = new Project();

        $this->specify("Project is OK", function() {
            $this->project->name = 'testproject';
            $this->assertTrue($this->project->validate());
        });

        $this->specify("Name is required", function() {
            $this->project->name = null;
            $this->assertFalse($this->project->validate());
        });

    }

}
