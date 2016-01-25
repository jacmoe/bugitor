<?php
namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\DbTestCase;
use Codeception\Specify;
//use common\models\LoginForm;
use tests\codeception\common\fixtures\ProjectFixture;

class ProjectTest extends DbTestCase
{
    use Specify;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'project' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/common/unit/fixtures/data/models/project.php'
            ],
        ];
    }

}
