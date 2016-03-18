<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class AuthAssignmentFixture extends ActiveFixture
{
    public $tableName = 'auth_assignment';//\Yii::$app->getAuthManager()->assignmentTable;
}
