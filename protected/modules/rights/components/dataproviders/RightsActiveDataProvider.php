<?php
/**
* Rights active data provider class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.10
*/
class RightsActiveDataProvider extends CActiveDataProvider
{
	private $_behaviors = array();

	/**
	* Fetches the data from the persistent data storage.
	* @return array list of data items
	*/
	public function fetchData()
	{
		$data = parent::fetchData();

		if( $this->_behaviors!==array() )
			foreach( $data as $model )
				$model->attachBehaviors($this->_behaviors);

		return $data;
	}

	/**
	* Sets the behaviors.
	* @param array $value list of behaviors to be attached.
	*/
	public function setBehaviors($value)
	{
		$this->_behaviors = $value;
	}
}
