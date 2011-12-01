<?php
require_once ( 'bbApiAbstract.php' );
 

/**
 * Encapsulates the accessors and methods of the Groups class
 * @author Anthony Steiner <asteiner@steinerd.com>
 *
 */
class bbApiGroups extends bbApiAbstract
{

	/**
	 * Members object, extending the groups class
	 * @var Members
	 */
	public $members;
		
	
	/**
	 * Uses the extended constructor as well as substantiating the members property
	 * @param bbApi $api
	 */
	public function __construct(bbApi $api)
	{
		parent::__construct($api);
		
		$this->members = new bbApiMembers($api);
	}
	
	/**
	 * Retrieves all the groups (and members) of the currently authenticated user	 
	 * @return	object				Object containing the information of the method request
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Groups#Groups-QueryingYourGroups 
	 */
	public function mine()
	{
		$username = null;
		
		$this->checkUsername( $username );
		
		return $this->show( $username );
	}


	/**
	 * Retrieves all the groups (and members) of the user specified (By username or email)
	 * @param	string	$credential		BitBucket username or validated email
	 * @return	object					Object containing the information of the method request
	 * 
	 * @link 	http://confluence.atlassian.com/display/BBDEV/Groups#Groups-QueryingYourGroups
	 */
	public function show( $credential)
	{
		$this->checkUsername( $credential );
		
		$response = $this->api->get("/groups/steinerd/" );
		
		return $response;
	}
	
	/**
	 * Add a new group
	 * @param	string	$group_name		New group name
	 * @param	string	$credential		BitBucket username or validated email
	 * @return	object					Object containing the information of the method request			
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Groups#Groups-AddingGroups
	 */
	public function add($group_name, $credential = null)
	{
		$this->checkUsername($credential);
		
		$this->checkPermission($permission);
		
		$response = $this->api->post("/groups/$credential/", array('name' => $group_name));
				
		return $response;
	}
			
	
	/**
	 * Update existing group
	 * @param	string		$group_name		Group name to udpate
	 * @param	string		$permission		Permission to update (none, read, write, admin)
	 * @param	boolean		$auto_add
	 * @param	string 		$new_name
	 * @param	string 		$credential		BitBucket username or validated email
	 * @return	object						Object containing the information of the method request	
	 * 
	 * @link	http://confluence.atlassian.com/display/BBDEV/Groups#Groups-UpdatingGroups
	 */
	public function update($group_name, $permission = bbApi_permission::READ, $auto_add = false, $new_name = null, $credential = null)
	{
		$this->checkUsername($credential);
		
		$this->checkPermission($permission);
		
		$new_name = $new_name ?: $group_name;
		$auto_add = $auto_add ? 'true' : 'false';
				
		$response = $this->api->put( "/groups/$credential/$group_name/", 
			array(
				'name' 			=> $new_name,
				'permission' 	=> $permission,
				'auto_add'		=> $auto_add
		));

		return $response;
	}
	
	/**
	 * Delete existing group
	 * @param	string		$group_name		Group name to delete
 	 * @param	string		$credential		BitBucket username or validated email		
	 * @return	object						Object containing the information of the method request	
	 */
	public function delete($group_name, $credential = null)
	{		
		$this->checkUsername($credential);

		$response = $this->api->delete( "/groups/$credential/$group_name/" );
		
		return $response;		
	}	
	
}

//TODO: Finish documentation
/**
 * Encapsulates the accessors and methods of the Groups' Members class
 * @author Anthony Steiner <asteiner@steinerd.com>
 *
 */
class bbApiMembers extends bbApiAbstract
{
	
	public function show($group_name, $credential = null)
	{
		$this->checkUsername($credential);
		
		$response = $this->api->get( "/groups/$credential/$group_name/members/" );

		return $response;
	}
	
	public function add($group_name, $new_member, $credential = null)
	{
		$this->checkUsername($credential);
		
		$response = $this->api->put( "/groups/$credential/$group_name/members/$new_member/" );
		
		return $response;
	}	
	
	public function delete($group_name, $member, $credential = null)
	{
		$this->checkUsername($credential);
		
		$response = $this->api->delete( "/groups/$credential/$group_name/members/$member/" );
		
		return $response;		
	}
}

/**
 * Encapsulates the accessors and methods of the Groups' Privileges class
 * @author Anthony Steiner <asteiner@steinerd.com>
 * @TODO: Add Privileges
 */
//class Privileges extends bbApiAbstract
//{
//
//}

