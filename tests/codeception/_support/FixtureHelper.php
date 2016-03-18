<?php

namespace tests\codeception\_support;

use tests\codeception\unit\fixtures\UserFixture;
use tests\codeception\unit\fixtures\AuthItemFixture;
use tests\codeception\unit\fixtures\AuthItemChildFixture;
use tests\codeception\unit\fixtures\AuthAssignmentFixture;
//use tests\codeception\unit\fixtures\AuthRuleFixture;
use Codeception\Module;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

/**
 * This helper is used to populate the database with needed fixtures before any tests are run.
 * In this example, the database is populated with the demo login user, which is used in acceptance
 * and functional tests.  All fixtures will be loaded before the suite is started and unloaded after it
 * completes.
 */
class FixtureHelper extends Module
{

    /**
     * Redeclare visibility because codeception includes all public methods that do not start with "_"
     * and are not excluded by module settings, in actor class.
     */
    use FixtureTrait {
        loadFixtures as public;
        fixtures as public;
        globalFixtures as public;
        createFixtures as public;
        unloadFixtures as public;
        getFixtures as public;
        getFixture as public;
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function globalFixtures()
    {
        return [
            InitDbFixture::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/user.php',
            ],
            'auth_item' => [
                'class' => AuthItemFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/auth_item.php',
            ],
            'auth_item_child' => [
                'class' => AuthItemChildFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/auth_item_child.php',
            ],
            'auth_assignment' => [
                'class' => AuthAssignmentFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/auth_assignment.php',
            ],
        ];
    }
}
