<?php


/** 
 * @author Anthony Steiner <asteiner@steinerd.com>
 * 
 * @TODO: Run tests
 */
class bbApiSshKeys extends bbApiAbstract
{
	
	public function show()
	{
		$username = null;
		$this->checkUsername($username);
		
		$response = $this->api->get( "/ssh-keys/" );
		
		return $response;
	}
	
	public function add($ssh_key)
	{
		$username = null;
		$this->checkUsername($username);

		$response = $this->api->post("/ssk-keys/", array('key', $ssh_key));
		
		return $response;
		
	}
	
	public function delete($pk)
	{
		$username = null;
		$this->checkUsername($username);

		$response = $this->api->delete("/ssk-keys/$id");
		
		return $response;
				
	}	

}

