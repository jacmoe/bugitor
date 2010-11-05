<?php
/**
* Rights helper class file.
*
* Provides static functions for interaction with Rights from outside of the module.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.1
*/
class Rights
{
	const PERM_NONE = 0;
	const PERM_DIRECT = 1;
	const PERM_INHERITED = 2;

	private static $_m;
	private static $_a;

	/**
	* Assigns an authorization item to a specific user.
	* @param string the name of the item to assign.
	* @param integer the user id of the user for which to assign the item.
	* @param string business rule associated with the item. This is a piece of
	* PHP code that will be executed when {@link checkAccess} is called for the item.
	* @param mixed additional data associated with the item.
	* @return CAuthItem the authorization item
	*/
	public static function assign($itemName, $userId, $bizRule=null, $data=null)
	{
		$authorizer = self::getAuthorizer();
		return $authorizer->authManager->assign($itemName, $userId, $bizRule, $data);
	}

	/**
	* Revokes an authorization item from a specific user.
	* @param string the name of the item to revoke.
	* @param integer the user id of the user for which to revoke the item.
	* @return boolean whether the item was removed.
	*/
	public static function revoke($itemName, $userId)
	{
		$authorizer = self::getAuthorizer();
		return $authorizer->authManager->revoke($itemName, $userId);
	}

	/**
	* Returns the roles assigned to a specific user.
	* If no user id is provided the logged in user will be used.
	* @param integer the user id of the user for which roles to get.
	* @param boolean whether to sort the items by their weights.
	* @return array the roles.
	*/
	public static function getAssignedRoles($userId=null, $sort=true)
	{
		$user = Yii::app()->getUser();
		if( $userId===null && $user->isGuest===false )
			$userId = $user->id;

	 	$authorizer = self::getAuthorizer();
	 	return $authorizer->getAuthItems(CAuthItem::TYPE_ROLE, $userId, null, $sort);
	}

	/**
	* Returns the base url to Rights.
	* @return the url to Rights.
	*/
	public static function getBaseUrl()
	{
		$module = self::module();
		return Yii::app()->createUrl($module->baseUrl);
	}

	/**
	* Returns the name of a specific authorization item.
	* @param integer the item type (0: operation, 1: task, 2: role).
	* @return string the authorization item type name.
	*/
	public static function getAuthItemTypeName($type)
	{
		switch( (int)$type )
		{
			case CAuthItem::TYPE_OPERATION: return Rights::t('core', 'Operation');
			case CAuthItem::TYPE_TASK: return Rights::t('core', 'Task');
			case CAuthItem::TYPE_ROLE: return Rights::t('core', 'Role');
			default: throw new CException(Rights::t('core', 'Invalid authorization item type.'));
		}
	}

	/**
	* Returns the name of a specific authorization item in plural.
	* @param integer the item type (0: operation, 1: task, 2: role).
	* @return string the authorization item type name.
	*/
	public static function getAuthItemTypeNamePlural($type)
	{
		switch( (int)$type )
		{
			case CAuthItem::TYPE_OPERATION: return Rights::t('core', 'Operations');
			case CAuthItem::TYPE_TASK: return Rights::t('core', 'Tasks');
			case CAuthItem::TYPE_ROLE: return Rights::t('core', 'Roles');
			default: throw new CException(Rights::t('core', 'Invalid authorization item type.'));
		}
	}

	/**
	* Returns the route to a specific authorization item list view.
	* @param integer $type the item type (0: operation, 1: task, 2: role).
	* @return array the route.
	*/
	public static function getAuthItemRoute($type)
	{
		switch( (int)$type )
		{
			case CAuthItem::TYPE_OPERATION: return 'authItem/operations';
			case CAuthItem::TYPE_TASK: return 'authItem/tasks';
			case CAuthItem::TYPE_ROLE: return 'authItem/roles';
			default: throw new CException(Rights::t('core', 'Invalid authorization item type.'));
		}
	}

	/**
	* Returns the valid child item types for a specific type.
	* @param string the authorization item type.
	* @return array the valid types.
	*/
	public static function getValidChildTypes($type)
	{
	 	switch( (int)$type )
		{
			// Roles can consist of any type of authorization items
			case CAuthItem::TYPE_ROLE: return null;
			// Tasks can consist of other tasks and operations
			case CAuthItem::TYPE_TASK: return array(CAuthItem::TYPE_TASK, CAuthItem::TYPE_OPERATION);
			// Operations can consist of other operations
			case CAuthItem::TYPE_OPERATION: return array(CAuthItem::TYPE_OPERATION);
			// Invalid type
			default: throw new CException(Rights::t('core', 'Invalid authorization item type.'));
		}
	}

	/**
	* @return string a string that can be displayed on your Web page
	* showing Powered-by-Rights information.
	*/
	public static function powered()
	{
		$module = self::module();
		return 'Access Control by <a href="http://code.google.com/p/yii-rights/" rel="external">Rights</a> version '.$module->getVersion().'.';
	}

	/**
	* @return RightsModule the Rights module.
	*/
	public static function module()
	{
		if( isset(self::$_m)===false )
			self::$_m = self::findModule();

		return self::$_m;
	}

	/**
	* Searches for the Rights module among all installed modules.
	* The module will be found even if it's nested within another module.
	* @param object the module to find the module in. Defaults to null,
	* meaning that the application will be used.
	* @return the Rights module.
	*/
	private static function findModule($module=null)
	{
		if( $module===null )
			$module = Yii::app();

		if( ($m = $module->getModule('rights'))!==null )
			return $m;

		foreach( $module->getModules() as $id=>$c )
			if( ($m = $module->getModule($id))!==null )
				if( ($result = self::findModule($m))!==null )
					return $result;

		return null;
	}

	/**
	* @return RightsAuthorizer the authorizer component.
	*/
	public static function getAuthorizer()
	{
		if( isset(self::$_a)===false )
			self::$_a = self::module()->getAuthorizer();

		return self::$_a;
	}

	/**
	* Translates a message to the specified language.
	* Wrapper class for setting the category correctly.
	* @param string message category.
	* @param string the original message.
	* @param array parameters to be applied to the message using <code>strtr</code>.
	* @param string which message source application component to use.
	* @param string the target language.
	* @return string the translated message.
	*/
	public static function t($category, $message, $params=array(), $source=null, $language=null)
	{
		return Yii::t('RightsModule.'.$category, $message, $params, $source, $language);
	}
}
