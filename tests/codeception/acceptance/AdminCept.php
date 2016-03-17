<?php
use bugitor\tests\AcceptanceTester\AdminSteps as AdminTester;

$I = new AdminTester($scenario);
$I->loginAsAdmin();
$I->seeLink('Dashboard');
$I->click('Dashboard');
$I->amOnPage('/user/admin');
$I->seeLink('Create');
