<?php

require_once ('bbApiAbstract.php');


/**
 * Encapsulates the accessors and methods of the Issues class
 * @author Anthony Steiner <asteiner@steinerd.com> 
 */
class bbApiIssues extends bbApiAbstract
{
	
	public function __construct(bbApi $api)
	{
		parent::__construct($api);
		
	}
	
    /**
     * Enter description here ...
     * @param unknown_type		$repository
     * @param unknown_type		$username
     * @param string|array		$filter
     * @param unknown_type		$id
     * @param unknown_type		$limit
     * @param unknown_type		$offsetStart
     * @return Ambiguous
     * @TODO correct documentation
     */
    public function show($repository, $filter = null, $id = 0, $limit = 25, $offsetStart = 0, $username = null)
    {
        $options = array();
        
        $this->checkUsername( $username );
        
        if ( ! is_numeric( $id ) || $id == 0 )
        {
            $options = array(
                    'start' => $offsetStart,
                    'limit' => $limit
            );
            
            $id = null;
        }
        elseif (is_numeric($id) && $id != 0)
        {
        	$id = $id . "/";
        }
        
        if ( is_array( $filter ) )
        {
            $options = array_merge( $options, $filter );
        }
        elseif ( is_string( $filter ) )
        {
            parse_str( $filter, $filter_array );
            $options = array_merge( $filter_array, $options );
        }
        
        $response = $this->api->get( "/repositories/$username/$repository/issues/$id", $options );
      
        return $response;
    }
    

	/**
	 * Searches repositories for items that match the search criterea 
	 * @param	string		$search 		search criterea
	 * @param	string		$repository		repository to search
	 * @param	string		$username		username of said repository
	 * @param	int			$limit			limit of records to display
	 * @param	int			$offsetStart	offset of records to start the object
	 * @return	object						JSON object containing the results of the bbApiRequest
	 */
	public function search($search, $repository, $username = null, $limit = 25, $offsetStart = 0)
	{				
		$this->checkUsername( $username );		
			
		$parameters = array(
				'search' => strtolower( urlencode( $search ) ),
				'start' => $offsetStart,
				'limit' => $limit
		);
		
		$response = $this->api->get( "/repositories/$username/$repository/issues/", $parameters );
		
		return $response;
	}
			

	
	/**
	 * Enter description here ...
	 * @param unknown_type $repository
	 * @param unknown_type $id
	 * @param unknown_type $username
	 * @TODO correct documentation 
	 */
	public function followers($repository, $id = 0, $username = null)
	{
		$this->checkUsername($username);
				
		isset($this->api->followers);
		
		$response = $this->api->followers->issues( $repository, $id, $username );
		
		return $response;
	}
	

	/**
	 * Enter description here ...
	 * @param unknown_type $count
	 * @param unknown_type $username
	 * @param unknown_type $limit
	 * @param unknown_type $offsetStart
	 * @TODO correct documentation 
	 */
	public function events($username = null, $limit = 25, $offsetStart = 0)
	{
		//TODO: Events ovirride
	}
	
	public function add($repository, $title, $content, $status = 1, $kind = 'bug', $username = null)
	{
		$this->checkUsername($username);
		
		$parameters = array(
				'title' => $title,
				'content' => $content,
				'status' => $status,
				'kind'	=> $kind				
		);			
		
		$response = $this->api->post("/repositories/$username/$repository/issues/", $parameters);
		
	
		return $response;
	}
	
	public function update($repository, $id, $title = null, $content = null, $status = 1, $kind = 'bug', $username = null)
	{
		$this->checkUsername($username);
		
		$parameters = array(
				'title' => $title,
				'content' => $content,
				'status' => $status,
				'kind'	=> $kind				
		);				

		$response = $this->api->put("/repositories/$username/$repository/issues/$id/", $parameters);
		
		return $response;
	}	
	
	public function delete($repository, $id, $username = null)
	{
		$this->checkUsername($username);

		$response = $this->api->delete("/repositories/$username/$repository/issues/$id/");
		
		return $response;		
	}
	
	
}
