<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2010 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php
/**
 * BugitorTimestampBehavior class file.
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

 /**
 * BugitorTimestampBehavior will automatically fill date and time related atributes.
 *
 * BugitorTimestampBehavior will automatically fill date and time related atributes when the active record
 * is created and/or upadated.
 * You may specify an active record model to use this behavior like so:
 * <pre>
 * public function behaviors(){
 * 	return array(
 * 		'BugitorTimestampBehavior' => array(
 * 			'class' => 'zii.behaviors.BugitorTimestampBehavior',
 * 			'createAttribute' => 'create_time_attribute',
 * 			'updateAttribute' => 'update_time_attribute',
 * 		)
 * 	);
 * }
 * </pre>
 * The {@link createAttribute} and {@link updateAttribute} options actually default to 'create_time' and 'update_time'
 * respectively, so it is not required that you configure them.  If you do not wish BugitorTimestampBehavior
 * to set a timestamp for record update or creation, set the corrisponding attribute option to null.
 *
 * By default, the update attribute is only set on record update.  If you also wish it to be set on record creation,
 * set the {@link setUpdateOnCreate} option to true.
 *
 * Although BugitorTimestampBehavior attempts to figure out on it's own what value to inject into the timestamp attribute,
 * you may specify a custom value to use instead via {@link timestampExpression}
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @version $Id: BugitorTimestampBehavior.php 2326 2010-08-20 17:02:07Z qiang.xue $
 * @package zii.behaviors
 * @since 1.1
 */

class BugitorTimestampBehavior extends CActiveRecordBehavior {
	/**
	* @var mixed The name of the attribute to store the creation time.  Set to null to not
	* use a timstamp for the creation attribute.  Defaults to 'create_time'
	*/
	public $createAttribute = 'create_time';
	/**
	* @var mixed The name of the attribute to store the modification time.  Set to null to not
	* use a timstamp for the update attribute.  Defaults to 'update_time'
	*/
	public $updateAttribute = 'update_time';

	/**
	* @var bool Whether to set the update attribute to the creation timestamp upon creation.
	* Otherwise it will be left alone.  Defaults to false.
	*/
	public $setUpdateOnCreate = true;

	/**
	* @var mixed The expression to use to generate the timestamp.  e.g. 'time()'.
	* Defaults to null meaning that we will attempt to figure out the appropriate timestamp
	* automatically.  If we fail at finding the appropriate timestamp, then it will
	* fall back to using the current UNIX timestamp
	*/
	public $timestampExpression=null;

	/**
	* @var array Maps column types to database method
	*/
	protected static $map = array(
			'datetime'=>'UTC_TIMESTAMP()',
			'timestamp'=>'UTC_TIMESTAMP()',
			'date'=>'UTC_TIMESTAMP()',
	);

	/**
	* Responds to {@link CModel::onBeforeSave} event.
	* Sets the values of the creation or modified attributes as configured
	*
	* @param CModelEvent event parameter
	*/
	public function beforeSave($event) {
		if ($this->getOwner()->getIsNewRecord() && ($this->createAttribute !== null)) {
			$this->getOwner()->{$this->createAttribute} = $this->getTimestampByAttribute($this->createAttribute);
		}
		if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateAttribute !== null)) {
			$this->getOwner()->{$this->updateAttribute} = $this->getTimestampByAttribute($this->updateAttribute);
		}
	}

	/**
	* Gets the approprate timestamp depending on the column type $attribute is
	*
	* @param string $attribute
	* @return mixed timestamp (eg unix timestamp or a mysql function)
	*/
	protected function getTimestampByAttribute($attribute) {
		if ($this->timestampExpression !== null)
			return @eval('return '.$this->timestampExpression.';');

		$columnType = $this->getOwner()->getTableSchema()->getColumn($attribute)->dbType;
		return $this->getTimestampByColumnType($columnType);
	}

	/**
	* Returns the approprate timestamp depending on $columnType
	*
	* @param string $columnType
	* @return mixed timestamp (eg unix timestamp or a mysql function)
	*/
	protected function getTimestampByColumnType($columnType) {
		//return isset(self::$map[$columnType]) ? new CDbExpression(self::$map[$columnType]) : time();
                return date("Y-m-d\TH:i:s\Z", time());//
	}
}
