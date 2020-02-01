<?php

/**
 * Custom wrapper for the CMS component. We use it to provide proper access 
 * control.
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
Yii::import('cms.components.Cms');

class TLKCms extends Cms
{
	
	/**
	 * Checks whether the current user should be able to edit nodes. The real 
	 * check is done by WebUser::isAdmin()
	 * @return boolean
	 */
	public function checkAccess()
	{
		return Yii::app()->user->isAdmin();
	}
	
	/**
	 * Returns whether a specific page is active. The parent implementation is 
	 * overridden in order to speed things up a bit
	 * @param string $name the content name
	 * @return boolean the result
	 */
	public function isActive($name)
	{
		// Get the node. We don't want to eager load unused relations so we 
		// can't use loadNode()
		$node = CmsNode::model()->findByAttributes(array('name'=>$name));

		$controller = Yii::app()->getController();
		return ($controller->module !== null
				&& $controller->module->id === 'cms'
				&& $controller->id === 'node'
				&& $controller->action->id === 'page'
				&& isset($_GET['id']) && $_GET['id'] === $node->id);
	}

}