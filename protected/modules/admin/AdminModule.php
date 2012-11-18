<?php

/**
 * The admin module
 */
class AdminModule extends CWebModule
{
	/**
	 * Initializes the module
	 */
	public function init()
	{
		// Import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
		
		// Change error action
		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'errorAction'=>'//admin/default/error',
			),
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
