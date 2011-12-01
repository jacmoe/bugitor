<?php


/**
 * Abstract class for bbApi classes
 *
 * @author Anthony Steiner <asteiner@steinerd.com>
 * @abstract
 */
abstract class bbApiAbstract 
{
		
	
    /**
     * The core API
     * @var bbApi
     */
    protected $api;
       
    /**
     * BitBucket API Abstract constructor
     * @param	bbApi	$api		Api object to load
     */
    public function __construct (bbApi $api)
    { 	    	
    	
        $this->api = $api;   
    }
        
    /**
     * Descendant-usable username check.
     * If the username parameter is null it will return the authenticated user to the referenced parameter, 
     * otherwise it uses the existing user
     * @param	string 	&$username	BitBucket user name
     * @final
     */
    public final function checkUsername(&$username = null)
    {
    	$username = isset( $username ) ? $username : $this->api->getAuthenticatedUser();
    }
    	
	/**
	 * Checks the permission string for valid input
	 * @param	string	$permission	The permission string from the invoking method
	 * @final
	 */
	public final function checkPermission( $permission )
	{	
		$refclass = new ReflectionClass( 'bbApi_permission' );
		$valid_permissions = $refclass->getConstants();
		
		if (!in_array($permission, $valid_permissions))
		{
			echo sprintf('Permission is not valid (%s)', implode(", ", $valid_permissions));
			exit();			
		}
	}
	
	public final function __isset($api)
	{
		if (!isset($api))
		{
			die(get_class($api)." is not loaded!");
		}
	}
}


