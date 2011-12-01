<?php

require_once ('bbApiRequestException.php');

/** 
 * Encapsulates the accessors and methods of the API's Request class
 * @author Anthony Steiner <asteiner@steinerd.com>
 * @access public
 */
class bbApiRequest
{
	
	/**
	 * Default options/settings
	 * @var array
	 */
	protected $options = array	(
									'protocol' => 'https',
									'url' => ':protocol://api.bitbucket.org/1.0/:path?format=:format',
									'format' => 'object',
									'user_agent' => 'php5-bitbucket-api (http://code.steinerd.com/php5-bitbucket-api',
									'timeout' => 10,
									'username' => null,
									'password' => null,
									'debug' => false,
									'custom_errors' => array()
								);
								
								
	/**
	 * History of the request class, for cache purposes
	 * @var array
	 */
	protected static $history = array();
	
	private $content_type = "";
	
	/**
	 * Default constructor
	 * @param		array		$options		Passed-in options to overwrite the default options
	 */
	public function __construct(array $options = array())
	{		
		$this->configure( $options );
	}
	
	
	/**
	 * Merges/Configures the passed-in options with the default options
	 * @param		array		$options		Passed-in options to be merged with the class defaults
	 * @return bbApiRequest
	 */
	public function configure(array $options)
	{
		$this->options = array_merge( $options, $this->options );
	}
	
	
	/**
	 * Enter description here ...
	 * @param		string		$apiPath		The segment of the url that dictates which API and event to use
	 * @param		array		$parameters		Additional parameters to send as data seperate from the url
	 * @param		string		$httpMethod		Standard HTTP/1.1 invokation method
	 * @param		array		$options		Passed-in options to override the default options
	 * @return		object						Object containing the returned web response
	 */
	public function send($apiPath, array $parameters = array(), $httpMethod = 'GET', array $options = array())
	{
		$initialOptions = null;
		$response = null;
		
		if ( ! empty( $options ) )
		{
			$initialOptions = $this->options;
			$this->configure( $options );
		}
		
		$response = $this->doSend( $apiPath, $parameters, $httpMethod );
		$response = $this->decodeResponse( $response );
		
		if ( isset( $initialOptions ) )
		{
			$this->options = $initialOptions;
		}
		
		return $response;
	}
	
	/**
	 * Override for {@link send()}; Sends a GET HTTP request
	 * @param		string		$apiPath		The segment of the url that dictates which API and event to use
	 * @param		array		$parameters		Additional parameters to send as data seperate from the url
	 * @param		array		$options		Passed-in options to override the default options
	 * @return		object						Object containing the returned web response		
	 */
	public function get($apiPath, array $parameters = array(), array $options = array())
	{
		return $this->send( $apiPath, $parameters, 'GET', $options );
	}
	
	/**
	 * Override for {@link send()}; Sends a POST HTTP request
	 * @param		string		$apiPath		The segment of the url that dictates which API and event to use
	 * @param		array		$parameters		Additional parameters to send as data seperate from the url
	 * @param		array		$options		Passed-in options to override the default options
	 * @return		object						Object containing the returned web response		
	 */
	public function post($apiPath, array $parameters = array(), array $options = array())
	{
		return $this->send( $apiPath, $parameters, 'POST', $options );
	}
	
	/**
	 * Override for {@link send()}; Sends a PUT HTTP request
	 * @param		string		$apiPath		The segment of the url that dictates which API and event to use
	 * @param		array		$parameters		Additional parameters to send as data seperate from the url
	 * @param		array		$options		Passed-in options to override the default options
	 * @return		object						Object containing the returned web response		
	 */	
	public function put($apiPath, array $parameters = array(), array $options = array())
	{
		return $this->send( $apiPath, $parameters, 'PUT', $options );
	}
	
	/**
	 * Override for {@link send()}; Sends a DELETE HTTP request
	 * @param		string		$apiPath		The segment of the url that dictates which API and event to use
	 * @param		array		$parameters		Additional parameters to send as data seperate from the url
	 * @param		array		$options		Passed-in options to override the default options
	 * @return		object						Object containing the returned web response		
	 */		
	public function delete($apiPath, array $parameters = array(), array $options = array())
	{
		return $this->send( $apiPath, $parameters, 'DELETE', $options );
	}

	/**
	 * Retreives current authenticated user from the class options property
	 * @return 		string						Currently authenticated user
	 */
	public function getAuthenticatedUser()
	{
		return $this->getOption('username');
	}
	
	
	/**
	 * Decodes the JSON text into a usable PHP stdObject
	 * @param		mixed		$response		The raw HTTP response from the BitBucket API	 
	 * @return		object						Object containing the returned web response
	 *
	 * @todo									Add raw-output functionality
	 * @throws 		bbApiRequestException		
	 */
	protected function decodeResponse($response)
	{	
		if ( $this->options['format'] === 'object' )
		{
			return json_decode( $response, false );
		}
					
		header ("Content-Type: {$this->content_type}");	

		return $response;							

				
		throw new Exception( __CLASS__ . ' does not support <em>' . $this->options['format'] . '</em> format.' );
	}
	

	/**
	 * The meat & potatoes... Sends all set parameters to the API url in hopes it returns a value, lol
	 * @param		string		$apiPath		The segment of the url that dictates which API and event to use
	 * @param		array		$parameters		Additional parameters to send as data seperate from the url
	 * @param		string		$httpMethod		Standard HTTP/1.1 invokation method 
	 * @return		mixed
	 * 
	 * @throws bbApiRequestException 
	 */
	public function doSend($apiPath, array $parameters = array(), $httpMethod = 'GET')
	{
		$this->updateHistory();
		$currentOptions = array();
		
		// Makes sure to convert object format to json so the library can decode it into a stdClass
		if ($this->options['format'] === 'object')
		{
			$currentOptions['format'] = 'json';
		}
		
		$currentOptions = array_merge( $this->options, $currentOptions );		
		
		
		
		$url = strtr( $this->options['url'], array(
				':protocol' => $this->options['protocol'],
				':path' => trim(implode( "/", array_map( 'urlencode', explode( "/", $apiPath ) ) ), '/') . (substr($apiPath, -1) == '/' ? '/' : ''),
				':format' => $currentOptions['format']				
		) );

		$curlOptions = array();
		
		if ( $currentOptions['username'] )
		{
			$curlOptions += array(
					CURLOPT_USERPWD => sprintf( '%s:%s', $currentOptions['username'], $currentOptions['password'] )
			);
		}
		

		
		if ( ! empty( $parameters ) )
		{

			$queryString = utf8_encode( http_build_query( $parameters, '', '&' ) );
			
			switch ( $httpMethod )
			{
				case 'GET':
					$url .= "&amp;".$queryString;
					break;
				case 'POST':
					$curlOptions += array(
							CURLOPT_POST => true,
							CURLOPT_POSTFIELDS => $queryString,
							CURLOPT_HTTPHEADER => array("Content-Length: ".strlen($queryString)."")
					);					
					break;
				case 'PUT':
					/* Prepare the data for HTTP PUT. */
					$putString = $queryString;
					$putData = tmpfile();
					fwrite($putData, $putString);
					fseek($putData, 0);
					 					
					$curlOptions += array(
							CURLOPT_PUT => true,
							CURLOPT_INFILE => $putData,
							CURLOPT_INFILESIZE => strlen($putString)
					);
					$this->debug( $putString );			
					break;
				default:
					$curlOptions += array(
							CURLOPT_POSTFIELDS => $queryString,
							CURLOPT_CUSTOMREQUEST => $httpMethod,
							CURLOPT_HTTPHEADER => array("Content-Length: ".strlen($queryString)."")							
					);
					break;
			}		
		}
		

		$this->debug( 'send ' . $httpMethod . ' request: ' . $url );
		
		$curlOptions += array(
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => $currentOptions['user_agent'],
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => $currentOptions['timeout'],
				CURLOPT_SSL_VERIFYPEER => PHP_OS === 'WINNT' ? false : true // If this is running on windows, assume cURL can't find the CA store		
		);
		
		$curl = curl_init();
		
		curl_setopt_array( $curl, $curlOptions );
		
		if ( ($response = curl_exec( $curl )) === false )
		{			
			throw new bbApiRequestException( 'cURL Error: ' . curl_error( $curl ), curl_errno( $curl ) );
		}


		
		$headers = curl_getinfo( $curl );
		$errorNumber = curl_errno( $curl );
		$errorMessage = curl_error( $curl );		
		
		curl_close( $curl );
		
		$this->content_type = $headers['content_type'];
		
		if ( ! in_array($headers['http_code'], bbApiRequestException::$acceptableCodes ) )
		{
			$custom_message = "";
			if ( array_key_exists($headers['http_code'], $currentOptions['custom_errors'] ) )
			{
				$custom_message = $currentOptions['custom_errors'][$headers['http_code']];
			}
			else
			{
				$custom_message = null;
			}
			
			if ($currentOptions['debug'])
			{
				throw new bbApiRequestException($custom_message, (int)$headers['http_code'] );
			}
			else
			{
				print_r($response);
				return $headers['http_code'];
			}
		}
		
		if ( ! empty( $errorNumber ) )
		{
			throw new bbApiRequestException( 'error ' . $errorNumber );
		}
		
		return $response;
	}
	
	/**
	 * Records the requests times
	 * When 30 request have been sent in less than a minute,
	 * sleeps for two second to prevent reaching the assumed BitBucket API limitation.
	 *
	 * @access protected
	 * @return void
	 */
	protected function updateHistory()
	{
		self::$history[] = time();
		
		if ( 30 === count( self::$history ) )
		{
			if ( reset( self::$history ) >= (time() - 30) )
			{
				sleep( 2 );
			}
			
			array_shift( self::$history );
		}
	}
	
	/**
	 * Sets an option on-the-fly
	 * @param		string 			$name		The option's name/key
	 * @param		mixed 			$value		The option's value
	 * @return		bbApiRequest				Returns instance of self 
	 */
	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
		
		return $this;
	}
	
	/**
	 * Enter description here ...
	 * @param		string 			$name		The option's name/key
	 * @param		mixed			$default	The object that returns in the event the option doesn't exists
	 * @return 		mixed						Either the option requested or the default value specified
	 */
	public function getOption($name, $default = null)
	{
		return isset( $this->options[$name] ) ? $this->options[$name] : $default;
	}
	
	/**
	 * Enables the class to output debug information, if it was enabled at run-time
	 * @param		string			$message	The debug message
	 */
	protected function debug($message)
	{
		if ( $this->options['debug'] )
		{
			print $message . "\n";
		}
	}

}