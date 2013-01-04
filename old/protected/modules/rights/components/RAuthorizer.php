<?php
/**
* Rights authorizer component class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.5
*/
class RAuthorizer extends CApplicationComponent
{
	/**
	* @property string the name of the superuser role.
	*/
	public $superuserName;

	private $_authManager;

	/**
	* Initializes the authorizer.
	*/
	public function init()
	{
		parent::init();

		$this->_authManager = Yii::app()->getAuthManager();
	}

	/**
	* Returns the a list of all roles.
	* @param boolean whether to include the superuser.
	* @param boolean whether to sort the items by their weights.
	* @return the roles.
	*/
	public function getRoles($includeSuperuser=true, $sort=true)
	{
		$exclude = $includeSuperuser===false ? array($this->superuserName) : array();
	 	$roles = $this->getAuthItems(CAuthItem::TYPE_ROLE, null, null, $sort, $exclude);
	 	$roles = $this->attachAuthItemBehavior($roles);
	 	return $roles;
	}

	/**
	* Creates an authorization item.
	* @param string the item name. This must be a unique identifier.
	* @param integer the item type (0: operation, 1: task, 2: role).
	* @param string description of the item
	* @param string business rule associated with the item. This is a piece of
	* PHP code that will be executed when {@link checkAccess} is called for the item.
	* @param mixed additional data associated with the item.
	* @return CAuthItem the authorization item
	*/
	public function createAuthItem($name, $type, $description='', $bizRule=null, $data=null)
	{
		$bizRule = $bizRule!=='' ? $bizRule : null;

		if( $data!==null )
			$data = $data!=='' ? $this->sanitizeExpression($data.';') : null;

		return $this->_authManager->createAuthItem($name, $type, $description, $bizRule, $data);
	}

	/**
	* Updates an authorization item.
	* @param string the item name. This must be a unique identifier.
	* @param integer the item type (0: operation, 1: task, 2: role).
	* @param string description of the item
	* @param string business rule associated with the item. This is a piece of
	* PHP code that will be executed when {@link checkAccess} is called for the item.
	* @param mixed additional data associated with the item.
	*/
	public function updateAuthItem($oldName, $name, $description='', $bizRule=null, $data=null)
	{
		$authItem = $this->_authManager->getAuthItem($oldName);
		$authItem->name = $name;
		$authItem->description = $description!=='' ? $description : null;
		$authItem->bizRule = $bizRule!=='' ? $bizRule : null;

		// Make sure that data is not already serialized.
		if( @unserialize($data)===false )
			$authItem->data = $data!=='' ? $this->sanitizeExpression($data.';') : null;

		$this->_authManager->saveAuthItem($authItem, $oldName);
	}

	/**
	 * Returns the authorization items of the specific type and user.
	 * @param integer the item type (0: operation, 1: task, 2: role). Defaults to null,
	 * meaning returning all items regardless of their type.
	 * @param mixed the user ID. Defaults to null, meaning returning all items even if
	 * they are not assigned to a user.
	 * @param CAuthItem the item for which to get the select options.
	 * @param boolean sort items by to weights.
	 * @param array the items to be excluded.
	 * @return array the authorization items of the specific type.
	 */
	public function getAuthItems($types=null, $userId=null, CAuthItem $parent=null, $sort=true, $exclude=array())
	{
		// We have none or a single type.
		if( $types!==(array)$types )
		{
			$items = $this->_authManager->getAuthItems($types, $userId, $sort);
		}
		// We have multiple types.
		else
		{
			$typeItemList = array();
			foreach( $types as $type )
				$typeItemList[ $type ] = $this->_authManager->getAuthItems($type, $userId, $sort);

			// Merge the authorization items preserving the keys.
			$items = array();
			foreach( $typeItemList as $typeItems )
				$items = $this->mergeAuthItems($items, $typeItems);
		}

		$items = $this->excludeInvalidAuthItems($items, $parent, $exclude);
		$items = $this->attachAuthItemBehavior($items, $userId, $parent);

		return $items;
	}

	/**
	* Merges two arrays with authorization items preserving the keys.
	* @param array the items to merge to.
	* @param array the items to merge from.
	* @return array the merged items.
	*/
	protected function mergeAuthItems($array1, $array2)
	{
		foreach( $array2 as $itemName=>$item )
			if( isset($array1[ $itemName ])===false )
				$array1[ $itemName ] = $item;

		return $array1;
	}

	/**
	* Excludes invalid authorization items.
	* When an item is provided its parents and children are excluded aswell.
	* @param array the authorization items to process.
	* @param CAuthItem the item to check valid authorization items for.
	* @param array additional items to be excluded.
	* @return array valid authorization items.
	*/
	protected function excludeInvalidAuthItems($items, CAuthItem $parent=null, $exclude=array())
	{
		// We are getting authorization items valid for a certain item
		// exclude its parents and children aswell.
		if( $parent!==null )
		{
		 	$exclude[] = $parent->name;
		 	foreach( $parent->getChildren() as $childName=>$child )
		 		$exclude[] = $childName;

		 	// Exclude the parents recursively to avoid inheritance loops.
		 	$parentNames = array_keys($this->getAuthItemParents($parent->name));
		 	$exclude = array_merge($parentNames, $exclude);
		}

		// Unset the items that are supposed to be excluded.
		foreach( $exclude as $itemName )
			if( isset($items[ $itemName ]) )
				unset($items[ $itemName ]);

		return $items;
	}

	/**
	* Returns the parents of the specified authorization item.
	* @param mixed the item name for which to get its parents.
	* @param integer the item type (0: operation, 1: task, 2: role). Defaults to null,
	* meaning returning all items regardless of their type.
	* @param string the name of the item in which permissions to search.
	* @param boolean whether we want the specified items parent or all parents.
	* @return array the names of the parent items.
	*/
	public function getAuthItemParents($item, $type=null, $parentName=null, $direct=false)
	{
		if( ($item instanceof CAuthItem)===false )
			$item = $this->_authManager->getAuthItem($item);

		$permissions = $this->getPermissions($parentName);
		$parentNames = $this->getAuthItemParentsRecursive($item->name, $permissions, $direct);
		$parents = $this->_authManager->getAuthItemsByNames($parentNames);
		$parents = $this->attachAuthItemBehavior($parents, null, $item);

		if( $type!==null )
			foreach( $parents as $parentName=>$parent )
				if( (int)$parent->type!==$type )
					unset($parents[ $parentName ]);

		return $parents;
	}

	/**
	* Returns the parents of the specified authorization item recursively.
	* @param string the item name for which to get its parents.
	* @param array the items to process.
	* @param boolean whether we want the specified items parent or all parents.
	* @return the names of the parents items recursively.
	*/
	private function getAuthItemParentsRecursive($itemName, $items, $direct)
	{
		$parents = array();
		foreach( $items as $childName=>$children )
		{
		 	if( $children!==array() )
		 	{
		 		if( isset($children[ $itemName ]) )
		 		{
		 			if( isset($parents[ $childName ])===false )
		 				$parents[ $childName ] = $childName;
				}
				else
				{
		 			if( ($p = $this->getAuthItemParentsRecursive($itemName, $children, $direct))!==array() )
		 			{
		 				if( $direct===false && isset($parents[ $childName ])===false )
		 					$parents[ $childName ] = $childName;

		 				$parents = array_merge($parents, $p);
					}
				}
			}
		}

		return $parents;
	}

	/**
	* Returns the children for the specified authorization item recursively.
	* @param mixed the item for which to get its children.
	* @param integer the item type (0: operation, 1: task, 2: role). Defaults to null,
	* meaning returning all items regardless of their type.
	* @return array the names of the item's children.
	*/
	public function getAuthItemChildren($item, $type=null)
	{
		if( ($item instanceof CAuthItem)===false )
			$item = $this->_authManager->getAuthItem($item);

		$childrenNames = array();
		foreach( $item->getChildren() as $childName=>$child )
			if( $type===null || (int)$child->type===$type )
				$childrenNames[] = $childName;

		$children = $this->_authManager->getAuthItemsByNames($childrenNames);
		$children = $this->attachAuthItemBehavior($children, null, $item);

		return $children;
	}

	/**
	* Attaches the rights authorization item behavior to the given item.
	* @param mixed the item or items to which attach the behavior.
	* @param int the ID of the user to which the item is assigned.
	* @param CAuthItem the parent of the given item.
	* @return mixed the item or items with the behavior attached.
	*/
	public function attachAuthItemBehavior($items, $userId=null, CAuthItem $parent=null)
	{
		// We have a single item.
		if( $items instanceof CAuthItem )
		{
			$items->attachBehavior('rights', new RAuthItemBehavior($userId, $parent));
		}
		// We have multiple items.
		else if( $items===(array)$items )
		{
			foreach( $items as $item )
				$item->attachBehavior('rights', new RAuthItemBehavior($userId, $parent));
		}

		return $items;
	}

	/**
	* Returns the users with superuser priviledges.
	* @return the superusers.
	*/
	public function getSuperusers()
	{
		$userClass = Rights::module()->userClass;
		$users = CActiveRecord::model($userClass)->findAll();
		$users = $this->attachUserBehavior($users);

		$superusers = array();
		foreach( $users as $user )
		{
			$items = $this->getAuthItems(CAuthItem::TYPE_ROLE, $user->getId());
			$items = $this->attachAuthItemBehavior($items, $user->id);

			if( isset($items[ $this->superuserName ]) )
				$superusers[] = $user->getName();
		}

		return $superusers;
	}

	/**
	* Attaches the rights user behavior to the given users.
	* @param mixed the user or users to which attach the behavior.
	* @return mixed the user or users with the behavior attached.
	*/
	public function attachUserBehavior($users)
	{
		$userClass = Rights::module()->userClass;

		// We have a single user.
		if( $users instanceof $userClass )
		{
			$users->attachBehavior('rights', new RUserBehavior);
		}
		// We have multiple user.
		else if( $users===(array)$users )
		{
			foreach( $users as $user )
				$user->attachBehavior('rights', new RUserBehavior);
		}

		return $users;
	}

	/**
	* Returns whether the user is a superuser.
	* @param integer the id of the user to do the check for.
	* @return boolean whether the user is a superuser.
	*/
	public function isSuperuser($userId)
	{
		$assignments = $this->_authManager->getAuthAssignments($userId);
		return isset($assignments[ $this->superuserName ]);
	}

	/**
	* Returns the permissions for a specific authorization item.
	* @param string the name of the item for which to get permissions. Defaults to null,
	* meaning that the full permission tree is returned.
	* @return the permission tree.
	*/
	public function getPermissions($itemName=null)
	{
		if( $itemName!==null )
		{
			$item = $this->_authManager->getAuthItem($itemName);
			$permissions = $this->getPermissionsRecursive($item);
		}
		else
		{
			foreach( $this->getRoles() as $roleName=>$role )
				$permissions[ $roleName ] = $this->getPermissionsRecursive($role);
		}

		return $permissions;
	}

	/**
	* Returns the permissions for a specific authorization item recursively.
	* @param CAuthItem the item for which to get permissions.
	* @return array the section of the permissions tree.
	*/
	private function getPermissionsRecursive(CAuthItem $item)
	{
		$permissions = array();
	 	foreach( $item->getChildren() as $childName=>$child )
	 	{
	 		$permissions[ $childName ] = array();
	 		if( ($grandChildren = $this->getPermissionsRecursive($child))!==array() )
				$permissions[ $childName ] = $grandChildren;
		}

		return $permissions;
	}

	/**
	* Returns the permission type for an authorization item.
	* @param string the name of the item to check permission for.
	* @param string the name of the item in which permissions to look.
	* @param array the permissions.
	* @return integer the permission type (0: None, 1: Direct, 2: Inherited).
	*/
	public function hasPermission($itemName, $parentName=null, $permissions=array())
	{
		if( $parentName!==null )
		{
			if( $parentName===$this->superuserName )
				return 1;

			$permissions = $this->getPermissions($parentName);
		}

		if( isset($permissions[ $itemName ]) )
			return 1;

		foreach( $permissions as $children )
			if( $children!==array() )
				if( $this->hasPermission($itemName, null, $children)>0 )
					return 2;

		return 0;
	}

	/**
	* Tries to sanitize code to make it safe for execution.
	* @param string the code to be execute.
	* @return mixed the return value of eval() or null if the code was unsafe to execute.
	*/
	protected function sanitizeExpression($code)
	{
		// Language consturcts.
		$languageConstructs = array(
			'echo',
			'empty',
			'isset',
			'unset',
			'exit',
			'die',
			'include',
			'include_once',
			'require',
			'require_once',
		);

		// Loop through the language constructs.
		foreach( $languageConstructs as $lc )
			if( preg_match('/'.$lc.'\ *\(?\ *[\"\']+/', $code)>0 )
				return null; // Language construct found, not safe for eval.

		// Get a list of all defined functions
		$definedFunctions = get_defined_functions();
		$functions = array_merge($definedFunctions['internal'], $definedFunctions['user']);

		// Loop through the functions and check the code for function calls.
		// Append a '(' to the functions to avoid confusion between e.g. array() and array_merge().
		foreach( $functions as $f )
			if( preg_match('/'.$f.'\ *\({1}/', $code)>0 )
				return null; // Function call found, not safe for eval.

		// Evaluate the safer code
		$result = @eval($code);

		// Return the evaluated code or null if the result was false.
		return $result!==false ? $result : null;
	}

	/**
	* @return RAuthManager the authorization manager.
	*/
	public function getAuthManager()
	{
		return $this->_authManager;
	}
}
