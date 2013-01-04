<?php





/**
 * Use of this is optional, I'm using it as an ad-hoc enum (since PHP doesn't natively support Enumerations) 
 * and I would like certain constants available from both Intellisense-enabled IDEs as well as being able to 
 * edit their values from one place. 
 * 
 * Any where you see this class being used, the equivilent string may be used. Like I said; it's fully optional. 
 *
 */
class bbApi_permission
{
	const NONE 	= 'none';
	
	const READ 	= 'read';
	
	const WRITE = 'write';
	
	const ADMIN = 'admin';
} 


class bbApi_format
{
	const OBJECT = 'object';

	const JSON = 'json';
	
	const XML = 'xml';
	
	const YAML = 'yaml';
}


