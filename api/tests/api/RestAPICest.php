<?php

class RestAPICest
{
    private $access_token = 'access-token=100-token';

    public function hello_test(ApiTester $I)
    {
        $I->wantTo('Test the hello REST API function');
        $I->amHttpAuthenticated('admin', 'admin');
        $I->sendGET('/hello?' . $this->access_token);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Hello World');
    }

    public function you_test(ApiTester $I)
    {
        $I->wantTo('Test the hello REST API function with argument');
        $I->amHttpAuthenticated('admin', 'admin');
        $I->sendGET('/hello/you?' . $this->access_token);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Hello you');
    }
}
