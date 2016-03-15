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

    public function testFindProjectByName()
    {
        $expectedAttrs = $this->project['carole'];
        $project = Project::find($expectedAttrs['id']);
        $this->assertNotNull($project);
    }
    // ...test methods...
}
