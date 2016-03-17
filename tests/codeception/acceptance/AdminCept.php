<?php
use bugitor\tests\AcceptanceTester\AdminSteps as AdminTester;

$I = new AdminTester($scenario);
$I->loginAsAdmin();
$I->expectTo('see logout link');
$I->see('Logout');
$I->expectTo('be able to go to the user admin page');
$I->amOnPage('/user/admin/index');
$I->see('Manage users');
