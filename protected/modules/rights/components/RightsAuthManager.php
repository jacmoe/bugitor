<?php
/**
* Rights authorization manager class file.
* Implements support for sorting of authorization items.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.7
*/
class RightsAuthManager extends CDbAuthManager
{
	public $itemWeightTable = 'AuthItemWeight';

	/**
	* Adds an item as a child of another item making sure that
	* the item doesn't already have this child.
	* @param string the parent item name
	* @param string the child item name
	* @throws CException if either parent or child doesn't exist or if a loop has been detected.
	*/
	public function addItemChild($itemName, $childName)
	{
		// Make sure that the item doesn't already have this child
		if( $this->hasItemChild($itemName, $childName)===false )
			return parent::addItemChild($itemName, $childName);
	}

	/**
	* Assigns an authorization item to a user making sure that
	* the user doesn't already have this assignment.
	* @param string the item name
	* @param mixed the user ID (see {@link IWebUser::getId})
	* @param string the business rule to be executed when {@link checkAccess} is called
	* for this particular authorization item.
	* @param mixed additional data associated with this assignment
	* @return CAuthAssignment the authorization assignment information.
	* @throws CException if the item does not exist or if the item has already been assigned to the user.
	*/
	public function assign($itemName, $userId, $bizRule=null, $data=null)
	{
		// Make sure that this user doesn't already have this assignment
		if( $this->getAuthAssignment($itemName, $userId)===null )
			return parent::assign($itemName, $userId, $bizRule, $data);
	}

	/**
	* Returns the authorization item with the specified name.
	* @param string the name of the item
	* @return CAuthItem the authorization item. Null if the item cannot be found.
	*/
	public function getAuthItem($name)
	{
		if( ($item = parent::getAuthItem($name))!==null )
		{
			$items = $this->processItems(array($item));
			$item = $items===(array)$items ? array_pop($items) : null;
		}

		return $item;
	}

	/**
	* Returns the authorization items of the specific type and user.
	* @param integer the item type (0: operation, 1: task, 2: role). Defaults to null,
	* meaning returning all items regardless of their type.
	* @param mixed the user ID. Defaults to null, meaning returning all items even if
	* they are not assigned to a user.
	* @param CAuthItem the authorization item the items belong to.
	* parent for the authorization item
	* @param boolean whether to sort the items according to their weights.
	* @return array the authorization items of the specific type.
	*/
	public function getAuthItems($type=null, $userId=null, CAuthItem $parent=null, $sort=false)
	{
		// We need to sort the items
		if( $sort===true )
		{
			if( $type===null && $userId===null )
			{
				$sql = "SELECT name,t1.type,description,t1.bizrule,t1.data,weight
					FROM {$this->itemTable} t1
					LEFT JOIN {$this->itemWeightTable} t2 ON name=itemname
					ORDER BY t1.type ASC, weight ASC";
				$command=$this->db->createCommand($sql);
			}
			else if( $userId===null )
			{
				$sql = "SELECT name,t1.type,description,t1.bizrule,t1.data,weight
					FROM {$this->itemTable} t1
					LEFT JOIN {$this->itemWeightTable} t2 ON name=itemname
					WHERE t1.type=:type
					ORDER BY t1.type ASC, weight ASC";
				$command=$this->db->createCommand($sql);
				$command->bindValue(':type', $type);
			}
			else if( $type===null )
			{
				$sql = "SELECT name,t1.type,description,t1.bizrule,t1.data,weight
					FROM {$this->itemTable} t1
					LEFT JOIN {$this->assignmentTable} t2 ON name=t2.itemname
					LEFT JOIN {$this->itemWeightTable} t3 ON name=t3.itemname
					WHERE userid=:userid
					ORDER BY t1.type ASC, weight ASC";
				$command=$this->db->createCommand($sql);
				$command->bindValue(':userid', $userId);
			}
			else
			{
				$sql = "SELECT name,t1.type,description,t1.bizrule,t1.data,weight
					FROM {$this->itemTable} t1
					LEFT JOIN {$this->assignmentTable} t2 ON name=t2.itemname
					LEFT JOIN {$this->itemWeightTable} t3 ON name=t3.itemname
					WHERE t1.type=:type AND userid=:userid
					ORDER BY t1.type ASC, weight ASC";
				$command=$this->db->createCommand($sql);
				$command->bindValue(':type', $type);
				$command->bindValue(':userid', $userId);
			}

			$items = array();
			foreach($command->queryAll() as $row)
				$items[ $row['name'] ] = new CAuthItem($this, $row['name'], $row['type'], $row['description'], $row['bizrule'], unserialize($row['data']));
		}
		// No sorting required
		else
		{
			$items = parent::getAuthItems($type, $userId);
		}

		// Process the items and attach necessary behaviors
		$items = $this->processItems($items, $userId, $parent);

		return $items;
	}

	/**
	* Returns the specified authorization items sorted by weights.
	* @param array the names of the authorization items to get.
	* @return array the authorization items.
	*/
	public function getAuthItemsByNames($names, CAuthItem $parent=null, $sort=true)
	{
		$items = array();

		if( $names!==array() )
		{
			foreach( $names as &$name )
				$name = $this->db->quoteValue($name);

			$condition = 'name IN ('.implode(', ',$names).')';

			if( $sort===true )
			{
				$sql = "SELECT name,t1.type,description,t1.bizrule,t1.data,weight
					FROM {$this->itemTable} t1
					LEFT JOIN {$this->itemWeightTable} t2 ON name=itemname
					WHERE $condition
					ORDER BY t1.type ASC, weight ASC";
				$command=$this->db->createCommand($sql);
			}
			else
			{
				$sql = "SELECT name,t1.type,description,t1.bizrule,t1.data
					FROM {$this->itemTable} t1
					WHERE $condition";
				$command=$this->db->createCommand($sql);
			}

			foreach($command->queryAll() as $row)
				$items[ $row['name'] ]=new CAuthItem($this, $row['name'], $row['type'], $row['description'], $row['bizrule'], unserialize($row['data']));
		}

		$items = $this->processItems($items, null, $parent);

		return $items;
	}

	/**
	* Processes the authorization items before returning them.
	* @param array the items to process
	* @param mixed the user ID. Defaults to null, meaning returning all items even if
	* they are not assigned to a user.
	* @param CAuthItem the authorization item the items belong to.
	* @return the processed authorization items.
	*/
	public function processItems($items, $userId=null, CAuthItem $parent=null)
	{
		foreach( $items as $i )
			$i->attachBehavior('rights', new RightsAuthItemBehavior($userId, $parent));

		return $items;
	}

	/**
	* Updates the authorization items weight.
	* @param array the result returned from jui-sortable.
	*/
	public function updateItemWeight($result)
	{
		foreach( $result as $weight=>$itemname )
		{
			// Check if the item already has a weight
			$sql = "SELECT COUNT(*) FROM {$this->itemWeightTable}
				WHERE itemname=:itemname";
			$command = $this->db->createCommand($sql);
			$command->bindValue(':itemname', $itemname);

			if( $command->queryScalar()>0 )
			{
				$sql = "UPDATE {$this->itemWeightTable}
					SET weight=:weight
					WHERE itemname=:itemname";
				$command = $this->db->createCommand($sql);
				$command->bindValue(':weight', $weight);
				$command->bindValue(':itemname', $itemname);
				$command->execute();
			}
			// Item does not have a weight, insert it
			else
			{
				if( ($item = $this->getAuthItem($itemname))!==null )
				{
					$sql = "INSERT INTO {$this->itemWeightTable} (itemname, type, weight)
						VALUES (:itemname, :type, :weight)";
					$command = $this->db->createCommand($sql);
					$command->bindValue(':itemname', $itemname);
					$command->bindValue(':type', $item->getType());
					$command->bindValue(':weight', $weight);
					$command->execute();
				}
			}
		}
	}
}
