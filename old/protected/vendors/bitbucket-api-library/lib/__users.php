<?php

require_once ('bbApiAbstract.php');

/**
 * Encapsulates the accessors and methods of the Users class
 * @author Anthony Steiner <asteiner@steinerd.com>
 *
 */
class bbApiUsers extends bbApiAbstract
{
	public function show($username = null)
	{
		$this->checkUsername( $username );
		
		$response = $this->api->get( "/users/$username/" );
		
		return $response;
	} 
	
	public function followers($username = null)
	{
		$this->checkUsername( $username );
		
		$response = $this->api->get( "/users/$username/followers/" );
		
		return $response;
	}
		
	/**
	 * Diplay events for a user
	 * @param	number		$limit			The amount of records to return
	 * @param	number		$offsetStart	The number of the record to start from (zero-based index)
	 * @param	string		$username		BitBucket Username
	 * @return	object						Object containing the information of the method request				
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Users#Users-GettingInformationaboutUserEvents	
	 */	
	public function events($limit = 25, $offsetStart = 0, $username = null)
	{
		$this->checkUsername( $username );
		
		$response = $this->api->get( "/users/$username/events/", array(
				'start' => $offsetStart,
				'limit' => $limit
		) );
		
		return $response;
	}

}