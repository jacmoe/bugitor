<?php

class SiteTest extends WebTestCase
{
	public function testIndex()
	{
		$this->open('');
		$this->assertTextPresent('Welcome');
	}

	public function testContact()
	{
		$this->open('site/contact');
		$this->assertTextPresent('Contact Us');
		$this->assertElementPresent('name=ContactForm[name]');

		$this->type('name=ContactForm[name]','tester');
		$this->type('name=ContactForm[email]','tester@example.com');
		$this->type('name=ContactForm[subject]','test subject');
		$this->clickAndWait("//input[@value='Submit']");
		$this->assertTextPresent('Body cannot be blank.');
	}

	public function testLoginLogout()
	{
		$this->open('');
		// ensure the user is logged out
		if($this->isTextPresent('Logout'))
			$this->clickAndWait('link=Logout');

////		// test login process, including validation
		$this->clickAndWait('link=Login');
		$this->assertElementPresent('name=UserLogin[username]');
		$this->type('name=UserLogin[username]','jacmoe');
		$this->clickAndWait("//input[@value='Login']");
		$this->assertTextPresent('password cannot be blank.');
		$this->type('name=UserLogin[password]','jake2383');
		$this->clickAndWait("//input[@value='Login']");
		$this->assertTextNotPresent('password cannot be blank.');
		$this->assertTextPresent('Logout');

////		// test logout process
		$this->assertTextNotPresent('Login');
		$this->clickAndWait('link=Logout');
		$this->assertTextPresent('Login');
	}
}
