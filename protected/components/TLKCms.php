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
	 * @var int the group ID that has administrative capabilities
	 */
	public $gid;

	/**
	 * Checks whether the current user should be able to edit nodes. The real 
	 * check is done by WebUser::isAdmin()
	 * @return boolean
	 */
	public function checkAccess()
	{
		return Yii::app()->user->isAdmin();
	}

}