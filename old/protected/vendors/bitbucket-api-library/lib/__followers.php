<?php

require_once ( 'bbApiAbstract.php' );

//TODO: Add documentation

class bbApiFollowers extends bbApiAbstract
{

	public function mine()
	{	
		return $this->user(null);
	}
	
	
	public function user($username = null)
	{
		$this->checkUsername( $username );
		
		$response = $this->api->users->show($username);
		
		return $response;
	}
	
	public function repository($repository, $username = null)
	{
		$this->api->setApi("repositories", $this);
		
		$this->checkUsername( $username );
		
		$response = $this->api->repositories->followers( $repository, $username );
		
		return $response;
	}	
	
		
	public function issues($repository, $id = 0, $username = null)
	{
		$this->checkUsername( $username );
		
		$response = $this->api->get( "/repositories/$username/$repository/issues/$id/followers/" );
		
		return $response;
	}	
	

}
