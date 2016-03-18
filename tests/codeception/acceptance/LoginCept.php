<?php
use bugitor\tests\AcceptanceTester;
use tests\codeception\_pages\LoginPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that login works');

$loginPage = LoginPage::openBy($I);

$I->see('Sign in', 'h3');

$I->amGoingTo('try to login with empty credentials');
$loginPage->login('', '');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Login cannot be blank.');
$I->see('Password cannot be blank.');

$I->amGoingTo('try to login with wrong credentials');
$loginPage->login('admin', 'wrong');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Invalid login or password');

$I->amGoingTo('try to login with correct credentials');
$user = $I->getFixture('user')->getModel('admin');
$loginPage->login($user->email, 'qwerty');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$I->expectTo('see admin link');
$I->see('User Admin');
$I->expectTo('see logout link');
$I->see('Logout');
$I->click('Logout');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}

$I->amGoingTo('try to login as admin');
$loginPage = LoginPage::openBy($I);
$loginPage->login('admin', 'qwerty');
if (method_exists($I, 'wait')) {
 $I->wait(3); // only for selenium
}
$I->expectTo('see admin link');
$I->see('User Admin');
