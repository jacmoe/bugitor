<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class AuthItemChildFixture extends ActiveFixture
{
    public $tableName = 'auth_item_child';//\Yii::$app->getAuthManager()->itemChildTable;
}
