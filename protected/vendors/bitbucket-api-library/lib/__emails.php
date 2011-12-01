<?php

require_once ('bbApiAbstract.php');

class bbApiEmails extends bbApiAbstract
{
	
	private $options = array('custom_errors' => array(
						400	=> "The email is not the correct syntax",
						401	=> "Authentication was not provided", 
						403	=> "Email is already in use on another account" 
						));
	
	public function mine($singular = null)
	{
				
		$response = $this->api->get( "/emails/$singular/" );
		
		return $response;
	}
	
	public function add($new_emailaddress)
	{					
		$response = $this->api->put("/emails/$new_emailaddress/");
		
		return $response;
	}

	public function set_primary($email_address)
	{		
		$response = $this->api->put("/emails/$email_address/", array('primary' => 'true'), $this->options );
		
		return $response;
	}
	
	public function delete($email_address)
	{
		$this->options['custom_errors'][401] += "\n\t-OR-\t\nYou're attempting to delete the primary email"; 
		
		$this->options = array('custom_errors' => array(
							404	=> "Email address not part of your account",							
							409	=> "You are attempting to delete the last email on your account" 
						));
		
		$response = $this->api->delete("/emails/$email_address/", array('primary' => 'true'), $this->options );
		
		return $response;
	}
	

	
}