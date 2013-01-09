<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	
	/**
	 * @var array the valid languages for this site
	 */
	public static $validLanguages = array(
		'sv'=>'Svenska',
		'en'=>'English',
	);

	protected $_pageTitle;

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	public function setPageTitle($value)
	{
		$this->_pageTitle = $value;
	}

	public function getPageTitle()
	{
		if ($this->_pageTitle !== null)
			return $this->_pageTitle.' | '.Yii::app()->name;
		else
			return Yii::app()->name;
	}

}