<?php
namespace bugitor\tests\AcceptanceTester;
use tests\codeception\_pages\LoginPage;

class AdminSteps extends \bugitor\tests\AcceptanceTester
{
    public function loginAsAdmin()
    {
        $I = $this;
        $user = $I->getFixture('user')->getModel('admin');
        $loginPage = LoginPage::openBy($I);
        $loginPage->login($user->email, 'qwerty');
    }
}