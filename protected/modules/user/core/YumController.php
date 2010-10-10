<?php
/**
 * Base controller class
 * @author tomasz.suchanek
 * @since 0.6
 * @package Yum.core
 *
 */
abstract class YumController extends CController
{
	public $breadcrumbs;
	public $menu;
	public $title='Change me!';

	public function filters()
	{
		return array(
			'accessControl',
		);
	}	
}
?>
