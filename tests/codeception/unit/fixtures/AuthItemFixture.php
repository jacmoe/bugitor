<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class AuthItemFixture extends ActiveFixture
{
    public $tableName = 'auth_item';//\Yii::$app->getAuthManager()->itemTable;
}
