<?php
/**
* Rights user behavior class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.10
*/
class RightsUserBehavior extends CModelBehavior
{
	/**
	* @property the name of the id column.
	*/
	public $idColumn;
	/**
	* @property the name of the name column.
	*/
	public $nameColumn;

	/**
	* Returns the value of the owner's id column.
	* Attribute name is retrived from the module configuration.
	* @return string the id.
	*/
	public function getId()
	{
		if( $this->idColumn===null )
			$this->idColumn = Rights::module()->userIdColumn;

		return $this->owner->{$this->idColumn};
	}

	/**
	* Returns the value of the owner's name column.
	* Attribute name is retrived from the module configuration.
	* @return string the name.
	*/
	public function getName()
	{
		if( $this->nameColumn===null )
			$this->nameColumn = Rights::module()->userNameColumn;

		return $this->owner->{$this->nameColumn};
	}

	/**
	* Returns a link labeled with the username pointing to the users assignments.
	* @return string the link markup.
	*/
	public function getAssignmentNameLink()
	{
		return CHtml::link($this->getName(), array('assignment/user', 'id'=>$this->getId()));
	}

	/**
	* Gets the users assignments.
	* @param boolean whether to display the authorization item type.
	* @return string the assignments markup.
	*/
	public function getAssignments($displayType=false)
	{
		$authorizer = Rights::getAuthorizer();
		$assignments = $authorizer->authManager->getAuthAssignments($this->getId());

		$items = $authorizer->authManager->getAuthItemsByNames(array_keys($assignments));
		$assignedItems = array();
		foreach( $items as $itemName=>$item )
		{
			$itemMarkup = $item->getNameText();

			if( $displayType===true )
				$itemMarkup .= ' (<span class="type-text">'.Rights::getAuthItemTypeName($item->type).'</span>)';

			$assignedItems[] = $itemMarkup;
		}

		return implode('<br />', $assignedItems);
	}
}