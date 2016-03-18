<?php
namespace tests\codeception\unit\models;

use yii\codeception\DbTestCase;
use dektrium\user\models\User;
use tests\codeception\unit\fixtures\UserFixture;
use tests\codeception\unit\fixtures\AuthItemFixture;
use tests\codeception\unit\fixtures\AuthItemChildFixture;
use tests\codeception\unit\fixtures\AuthAssignmentFixture;
//use tests\codeception\unit\fixtures\AuthRuleFixture;

class UserTest extends DbTestCase
{
    use \Codeception\Specify;

    public function fixtures()
    {
        return [
            'user' => UserFixture::className(),
            'auth_item' => AuthItemFixture::className(),
            'auth_item_child' => AuthItemChildFixture::className(),
            'auth_assignment' => AuthAssignmentFixture::className(),
        ];
    }

    public function testCreate()
    {
    }

}
