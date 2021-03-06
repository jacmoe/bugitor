<?php
use bugitor\tests\AcceptanceTester;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Bugitor');
$I->seeLink('Projects');
$I->click('Projects');
$I->see('Projects all your bugs are belong to us');
