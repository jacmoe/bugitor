<?php

/**
 * Encapsulates the accessors and methods of the Source class 
 * @author Anthony Steiner <asteiner@steinerd.com>
 * 
 * 
 */
class bbApiSource extends bbApiAbstract
{
	public function show($repository, $location = null, $revision = 'tip', $username = null)
	{
		$this->api->setApi("repositories", $this);
				
		$response = $this->api->repositories->source( $repository, $location, $revision, $username );
		
		return $response;
	}		 
}
 