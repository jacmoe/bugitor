<?php
  
require_once ('bbApiAbstract.php');
   
/**
 * Encapsulates the accessors and methods of the Changesets class
 * @author Anthony Steiner <asteiner@steinerd.com>
 *
 */
class bbApiChangesets extends bbApiAbstract
{
	/**
	 * Display a list or singular changeset(s)
	 * 
	 * Example:
	 * <code>
	 * <?php
	 * require_once 'bbApi.php';
	 * 
	 * $bb = new bbApi();
	 * 
	 * $bb->authenticate('<your username>', '<your password>');
	 * 
	 * print_r($bb->changesets->show("<your repository>"));
	 * ?>
	 * </code>
	 * @param		string		$repository			Repository slug
	 * @param		string		$username			Username of the repository 
	 * @param		string		$node				Changeset node
	 * @param		number		$limit				Amount of records to return
	 * @param		number		$offsetStart		Record index to begin object (zero-based index)
	 */ 	
	public function show($repository, $username = null, $node = null, $offsetStart = 0, $limit = 15)
	{
		$this->api->setApi("repositories", $this);
		
		return $this->api->repositories->changesets($repository, $username, $node, $offsetStart, $limit);
	}
}