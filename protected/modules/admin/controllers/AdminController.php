<?php

/**
 * Base controller for all controllers in this module
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class AdminController extends Controller
{

	/**
	 * Defines the filters for this controller
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * Defines the access rules for this controller. By default all actions 
	 * inside this module is available only to administrators.
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'expression'=>'Yii::app()->user->isAdmin()',
			),
			array('deny'),
		);
	}

	/**
	 * Returns the page title
	 * @return string
	 */
	public function getPageTitle()
	{
		if ($this->_pageTitle !== null)
			return parent::getPageTitle();
		else
			return 'Administration - LAN-klubben';
	}

}