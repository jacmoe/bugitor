<?php
/**
* Auth item child form class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9
*/
class AuthChildForm extends CFormModel
{
	public $name;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('name', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Rights::t('core', 'Authorization item'),
		);
	}
}
