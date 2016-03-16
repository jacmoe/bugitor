<?php
namespace tests\codeception\unit\models;

use yii\codeception\DbTestCase;
use dektrium\user\models\User;
use tests\codeception\unit\fixtures\UserFixture;

class UserTest extends DbTestCase
{
    use \Codeception\Specify;

    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
        ];
    }

    public function testCreate()
    {
    }

}
