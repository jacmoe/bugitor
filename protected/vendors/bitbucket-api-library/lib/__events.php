<?php

/** 
 * Encapsulates the accessors and methods of the Events class
 * @author Anthony Steiner <asteiner@steinerd.com>
 *   
 */
class bbApiEvents extends bbApiAbstract
{
	
	/**
	 * Diplay events for the currently authenticated user
	 * @param	number		$limit			The amount of records to return
	 * @param	number		$offsetStart	The number of the record to start from (zero-based index)
	 * @return	object						Object containing the information of the method request				
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Events#Events-GettingaListofEventsforaUser
	 * @see		Users::events()
	 */
	public function mine($limit = 25, $offsetStart = 0)
	{		
		$username = null;
				
		return $this->user($limit, $offsetStart, $username );;
	}	
	
	/**
	 * Diplay events for a user
	 * @param	number		$limit			The amount of records to return
	 * @param	number		$offsetStart	The number of the record to start from (zero-based index)
	 * @param	string		$username		BitBucket Username
	 * @return	object						Object containing the information of the method request				
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Events#Events-GettingaListofEventsforaUser	
	 * @see		Users::events()
	 */
	public function user($limit = 25, $offsetStart = 0, $username = null)
	{
		$this->api->setApi("users", $this);
				
		$response = $this->api->users->events($limit, $offsetStart, $username );
		
		return $response;
	}	
	
	/**
	 * Display events from a specific repostiroy
	 * @param	string		$repository		Repository slug
	 * @param	number		$limit			The amount of records to return
	 * @param	number		$offsetStart	The number of the record to start from (zero-based index)
	 * @param	string		$username		BitBucket Username
	 * @return	object						Object containing the information of the method request	
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Events#Events-GettingaListofEventsforaRepository
	 * @see		Repositories::events()
	 */
	
	public function repository($repository, $limit = 25, $offsetStart = 0, $username = null)
	{
		$this->api->setApi("repositories", $this);
				
		$response = $this->api->repositories->events($repository, $limit, $offsetStart, $username );
		
		return $response;
	}

	

	
}