<?php
 
require_once ('bbApiAbstract.php');

class bbApiRepositories extends bbApiAbstract
{

	public function changesets($repository, $username = null, $node = null, $offsetStart = 0, $limit = 25)
	{
		$this->checkUsername( $username );
		
		$response = $this->api->get( "/repositories/$username/$repository/changesets/$node/", array('start' => $offsetStart, 'limit' => $limit));
				/*'start' => $offsetStart,
				'limit' => $limit
		) );*/
		
		return $response;	
	}
	
	public function followers($repository, $username = null)
	{
		$this->checkUsername($username);
		
		$response = $this->api->get( "/repositories/$username/$repository/followers/" );
		
		return $response;		
		
	}
	 
	public function events($repository, $limit = 25, $offsetStart = 0, $username = null)
	{
		$this->checkUsername($username);
		
		$response = $this->api->get( "/repositories/$username/$repository/events/", array(
				'start' => $offsetStart,
				'limit' => $limit
		) );
		
		return $response;
	}
	
	public function source($repository, $location = null, $revision = 'tip', $username = null)
	{
		$this->checkUsername($username);
		
		$response = $this->api->get( "/repositories/$username/$repository/src/$revision/$location" );
		
		return $response;		
	}
	
}