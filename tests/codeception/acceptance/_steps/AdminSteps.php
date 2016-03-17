<?php
namespace bugitor\tests\AcceptanceTester;
use tests\codeception\_pages\LoginPage;

class AdminSteps extends \bugitor\tests\AcceptanceTester
{
    public function loginAsAdmin()
    {
        $I = $this;
        $loginPage = LoginPage::openBy($I);
        $loginPage->login('admin@admin.com', 'qwerty');
    }
}