<?php

/**
 * Enter description here ...
 * @author Anthony Steiner <asteiner@steinerd.com>
 * @todo Add documentation
 */
class bbApiWiki extends bbApiAbstract
{

	public function show($repository, $page = 'Home', $username = null)
	{
		
		$this->checkUsername($username);
		
		$response = $this->api->get("/repositories/$username/$repository/wiki/$page/");
		
		return $response;
	}
	
	public function add($repository, $page, $data, $username = null)
	{
		$this->checkUsername($username);
				
		$response = $this->api->post("/repositories/$username/$repository/wiki/$page/", array('data' => $data));
		
		if (!is_numeric($response))
		{	
			return $this->show($repository, $page, $username);
		}
		else 
		{
			return "";	
		}
	}	
	
	public function update($repository, $page, $data, $username = null)
	{
		$this->checkUsername($username);
		
		$response = $this->api->put("/repositories/$username/$repository/wiki/$page/", array('data' => $data));
		
		if (!is_numeric($response))
		{	
			return $this->show($repository, $page, $username);
		}
		else 
		{
			return "";	
		}
	}	
	

}
