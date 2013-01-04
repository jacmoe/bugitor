<?php

include('lib/bbApiConstants.php');
        
class bbApi
{

	/*----- Api Properties ---- */
	
	
	/**
	 * Changeset API
	 * @var Changesets
	 */
	public $changesets;

	/**
	 * Email API
	 * @var Emails
	 */
	public $emails;

	/**
	 * Events API
	 * @var Events
	 */
	public $events;

	/**
	 * Followers API
	 * @var Followers
	 */
	public $followers;

	/**
	 * Groups API
	 * @var Groups
	 */
	public $groups;

	/**
	 * Invitations API
	 * @var Invitations
	 */
	public $invitations;

	/**
	 * Issues API
	 * @var Issues
	 */
	public $issues;

	/**
	 * Privileges API
	 * @var Privileges
	 */
	public $privileges;

	/**
	 * Repositories API
	 * @var Repositories
	 */
	public $repositories;

	/**
	 * Services API
	 * @var Services
	 */
	public $services;

	/**
	 * Source API
	 * @var Source
	 */
	public $source;

	/**
	 * SSH Keys API
	 * @var SSH_Keys
	 */
	public $ssh_keys;

	/**
	 * Users API
	 * @var Users
	 */
	public $users;

	/**
	 * Wiki API
	 * @var Wiki
	 */
	public $wiki;
	
	/*----- /END Api Properties ---- */	
	
	
	/**
	 * Request object
	 * @access protected
	 * @var bbApiRequest
	 */
	protected $request = null;
	
	
	/**
	 * Collection of API Objects
	 * @access protected
	 * @var array(bbApiAbstract)
	 */
	protected $apis = array();
	
	/**
	 * Logical boolean whether or not to print debug information
	 * @access protected
	 * @var boolean
	 */
	protected $debug;
	
	/**
	 * Default constructor, assigns APIs via {@see bbApi::assign_apis()}
	 * @param		boolean		$debug		Logical boolean flag that dictates whether or not to print debug information
	 */
	public function __construct($out_format = bbApi_format::OBJECT , $debug = false)
	{
			
		$this->setOption('format', $out_format);
		
		$this->debug = $debug;
		
		$this->assign_apis();
		
	}

	/**
	 * Enter description here ...
	 */
	private function assign_apis()
	{
		$refclass = new ReflectionClass( $this );
		foreach( $refclass->getProperties(ReflectionProperty::IS_PUBLIC) as $property )
		{
			$name = $property->name;
			if ( $property->class == $refclass->name )
			{
				$this->{$property->name} = $this->getApi($property->name);
			}
		}
	}

	
	public function get_classProperties( $class )
	{
		$class_properties = (object)array();
		
		$refclass = new ReflectionClass( $class );
		foreach( $refclass->getProperties( ReflectionProperty::IS_PUBLIC ) as $property )
		{
			$name = $property->name;
			if ( ($property->class == $refclass->name) )
			{				
				$class_properties->{$property->name} = $class->{$property->name};
			}
		}	

		return $class_properties;
	}
	
	
	public function authenticate($username, $password)
	{
		$this->getRequest()->setOption( 'username', $username )->setOption( 'password', $password );
		
		return $this;
	}
	
	public function deAuthenticate()
	{
		return $this->authenticate( null, null );
	}
	
	public function get($route, array $parameters = array(), array $requestOptions = array())
	{
		return $this->getRequest()->get( $route, $parameters, $requestOptions );
	}
	
	public function post($route, array $parameters = array(), array $requestOptions = array())
	{
		return $this->getRequest()->post( $route, $parameters, $requestOptions );
	}
	
	public function put($route, array $parameters = array(), array $requestOptions = array())
	{
		return $this->getRequest()->put( $route, $parameters, $requestOptions );
	}
	
	public function delete($route, array $parameters = array(), array $requestOptions = array())
	{
		return $this->getRequest()->delete( $route, $parameters, $requestOptions );
	}
	
	public function getAuthenticatedUser()
	{
		return $this->getRequest()->getAuthenticatedUser();
	}
	
	public function setOption($name, $value)
	{
		$this->getRequest()->setOption($name, $value);
	}
	
	public function getOption($name, $default = null)
	{
		return $this->getRequest()->getOption($name, $default);
	}
		
	
	public function getRequest()
	{
		if ( ! isset( $this->request ) )
		{
			require_once (dirname( __FILE__ ) . '/lib/bbApiRequest.php');
			
			$this->request = new bbApiRequest( array('debug' => $this->debug));
		}
		
		return $this->request;
	}
	
	public function setRequest(bbApiRequest $request)
	{
		$this->request = $request;
		
		return $this;
	}
	
	public function getApi($name)
	{
       
        if ( ! isset( $this->apis[$name] ) )
        {
        	$class_name = "bbApi".implode("", array_map("ucfirst", explode("_", $name)));
        	$api_file = sprintf("/lib/__$name.php"); 

        	if ( ! file_exists(dirname( __FILE__ ) . $api_file)) { return null; }
        	      	
            require_once (dirname( __FILE__ ) . $api_file );
            
            if (!class_exists($class_name)) { return null; }
            
            $this->apis[$name] = new $class_name( $this );
        }
        
		return $this->apis[$name];
	}
	
	public function setApi($name, bbApiAbstract $instance)
	{
		$this->apis[$name] = $instance;
		
		return $this;
	}

}