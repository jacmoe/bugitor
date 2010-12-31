<?php
/**
* Rights authorization item behavior class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.11
*/
class RightsAuthItemBehavior extends CBehavior
{
	/**
	* @property integer the id of the user to whom this item is assigned.
	*/
	public $userId;
	/**
	* @property CAuthItem the parent item.
	*/
	public $parent;
	/**
	* @property integer the amount of children this item has.
	*/
	public $childCount;

	/**
	* Constructs the behavior.
	* @param integer the id of the user to whom this item is assigned
	* @param CAuthItem the parent item.
	*/
	public function __construct($userId=null, CAuthItem $parent=null)
	{
		$this->userId = $userId;
		$this->parent = $parent;
	}

	/**
	* Returns the markup for the item name.
	* @param boolean whether to display the human readable or system name.
	* @return string the markup.
	*/
	public function getNameText($humanReadable=true)
	{
		return ($humanReadable===true && $this->owner->description!==null) ? $this->owner->description : $this->owner->name;
	}

	/**
	* Returns the markup for the name link.
	* @param boolean whether to display the human readable or system name.
	* @param boolean whether to display the child count.
	* @param boolean whether to display the sortable id.
	* @return string the markup.
	*/
	public function getNameLink($humanReadable=true, $displayChildCount=false, $displaySortableId=false)
	{
		$markup = CHtml::link($this->getNameText($humanReadable), array(
			'authItem/update',
			'name'=>$this->owner->name,
			'redirect'=>urlencode(Rights::getAuthItemRoute($this->owner->type))
		));

		if( $displayChildCount===true )
			$markup .= $this->childCount();

		if( $displaySortableId===true )
			$markup .= $this->sortableId();

		return $markup;
	}

	/**
	* Returns the markup for the child count.
	* @return string the markup.
	*/
	public function childCount()
	{
		if( $this->childCount===null )
			$this->childCount = count($this->owner->getChildren());

		return $this->childCount>0 ? ' [ <span class="child-count">'.$this->childCount.'</span> ]' : '';
	}

	/**
	* Returns the markup for the id required by jui sortable.
	* @return string the markup.
	*/
	public function sortableId()
	{
	 	return ' <span class="auth-item-name" style="display:none;">'.$this->owner->name.'</span>';
	}

	/**
	* Returns the markup for the item type.
	* @return string the markup.
	*/
	public function getTypeText()
	{
		return Rights::getAuthItemTypeName($this->owner->type);
	}

	/**
	* Returns the markup for the delete operation link.
	* @return string the markup.
	*/
	public function getDeleteOperationLink()
	{
		return CHtml::linkButton(Rights::t('core', 'Delete'), array(
			'submit'=>array('authItem/delete', 'name'=>$this->owner->name, 'redirect'=>urlencode('authItem/operations')),
			'confirm'=>Rights::t('core', 'Are you sure you want to delete this operation?'),
			'class'=>'delete-link',
		));
	}

	/**
	* Returns the markup for the delete task link.
	* @return string the markup.
	*/
	public function getDeleteTaskLink()
	{
		return CHtml::linkButton(Rights::t('core', 'Delete'), array(
			'submit'=>array('authItem/delete', 'name'=>$this->owner->name, 'redirect'=>urlencode('authItem/tasks')),
			'confirm'=>Rights::t('core', 'Are you sure you want to delete this task?'),
			'class'=>'delete-link',
		));
	}

	/**
	* Returns the markup for the delete role link.
	* @return string the markup.
	*/
	public function getDeleteRoleLink()
	{
		return CHtml::linkButton(Rights::t('core', 'Delete'), array(
			'submit'=>array('authItem/delete', 'name'=>$this->owner->name, 'redirect'=>urlencode('authItem/roles')),
			'confirm'=>Rights::t('core', 'Are you sure you want to delete this role?'),
			'class'=>'delete-link',
		));
	}

	/**
	* Returns the markup for the remove parent link.
	* @return string the markup.
	*/
	public function getRemoveParentLink()
	{
		return CHtml::linkButton(Rights::t('core', 'Remove'), array(
			'submit'=>array('authItem/removeChild', 'name'=>$this->owner->name, 'child'=>$this->parent->name),
			'class'=>'remove-link',
		));
	}

	/**
	* Returns the markup for the remove child link.
	* @return string the markup.
	*/
	public function getRemoveChildLink()
	{
		return CHtml::linkButton(Rights::t('core', 'Remove'), array(
			'submit'=>array('authItem/removeChild', 'name'=>$this->parent->name, 'child'=>$this->owner->name),
			'class'=>'remove-link',
		));
	}

	/**
	* Returns the markup for the revoke assignment link.
	* @return string the markup.
	*/
	public function getRevokeAssignmentLink()
	{
		return CHtml::linkButton(Rights::t('core', 'Revoke'), array(
			'submit'=>array('assignment/revoke', 'id'=>$this->userId, 'name'=>$this->owner->name),
			'class'=>'revoke-link',
		));
	}

	/**
	* Returns the markup for the revoke permission link.
	* @param CAuthItem $role the role the permission is for.
	* @return string the markup.
	*/
	public function getRevokePermissionLink(CAuthItem $role)
	{
		$this->parent = $role;

		$app = Yii::app();
		$baseUrl = Rights::module()->baseUrl.'/';
		$csrf = ($csrf = $this->getCsrfValidationParam())!==null ? ', '.$csrf : '';
		$onclick = <<<EOD
jQuery.ajax({
	type:'POST',
	url:'{$app->createUrl($baseUrl.'authItem/revoke', array('name'=>$this->parent->name, 'child'=>$this->owner->name))}',
	data:{
		ajax:1
		$csrf
	},
	success:function() {
		$("#permissions").load('{$app->createUrl($baseUrl.'authItem/permissions')}', {
			ajax:1
			$csrf
		});
	}
});

return false;
EOD;
		return CHtml::link(Rights::t('core', 'Revoke'), '#', array(
			'onclick'=>$onclick,
			'class'=>'revoke-link',
		));
	}

	/**
	* Returns the markup for the assign permission link.
	* @param CAuthItem $role the role the permission is for.
	* @return string the markup.
	*/
	public function getAssignPermissionLink(CAuthItem $role)
	{
		$this->parent = $role;

		$app = Yii::app();
		$baseUrl = Rights::module()->baseUrl.'/';
		$csrf = ($csrf = $this->getCsrfValidationParam())!==null ? ', '.$csrf : '';
		$onclick = <<<EOD
jQuery.ajax({
	type:'POST',
	url:'{$app->createUrl($baseUrl.'authItem/assign', array('name'=>$this->parent->name, 'child'=>$this->owner->name))}',
	data:{
		ajax:1
		$csrf
	},
	success:function() {
		$("#permissions").load('{$app->createUrl($baseUrl.'authItem/permissions')}', {
			ajax:1
			$csrf
		});
	}
});

return false;
EOD;
		return CHtml::link(Rights::t('core', 'Assign'), '#', array(
			'onclick'=>$onclick,
			'class'=>'assign-link',
		));
	}

	/**
	* Returns the markup for a inherited permission.
	* @param array the parents for this item.
	* @param boolean whether to display the parent item type.
	* @return string the markup.
	*/
	public function getInheritedPermissionText($parents, $displayType=false)
	{
		$items = array();
		foreach( $parents as $itemName=>$item )
		{
			$itemMarkup = $item->getNameText();

			if( $displayType===true )
				$itemMarkup .= ' ('.Rights::getAuthItemTypeName($item->type).')';

			$items[] = $itemMarkup;
		}

		return '<span class="inherited-item" title="'.implode('<br />', $items).'">'.Rights::t('core', 'Inherited').' *</span>';
	}

	/**
	* Returns the cross-site request forgery parameter for Ajax-requests.
	* Null is returned if csrf-validation is disabled.
	* @return string the csrf parameter.
	*/
	public static function getCsrfValidationParam()
	{
		if( Yii::app()->request->enableCsrfValidation===true )
		{
	        $csrfTokenName = Yii::app()->request->csrfTokenName;
	        $csrfToken = Yii::app()->request->csrfToken;
	        return "'$csrfTokenName':'$csrfToken'";
		}
		else
		{
			return null;
		}
	}
}
