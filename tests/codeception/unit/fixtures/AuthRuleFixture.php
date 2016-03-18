<?php
namespace tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

class AuthRuleFixture extends ActiveFixture
{
    public $tableName = 'auth_rule';//\Yii::$app->getAuthManager()->ruleTable;
}
