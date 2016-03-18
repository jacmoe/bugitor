<?php
use bugitor\tests\AcceptanceTester\AdminSteps as AdminTester;

$I = new AdminTester($scenario);
$I->loginAsAdmin();
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see logout link');
$I->see('Logout');
$I->expectTo('see admin link');
$I->see('User Admin');
$I->expectTo('be able to go to the user admin page');
$I->click('User Admin');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('be on the User admin page');
$I->see('Manage users');

$I->click('Logout');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see login link');
$I->see('Login');
