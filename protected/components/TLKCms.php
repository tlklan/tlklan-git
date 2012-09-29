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
	 * Checks whether the current user should be able to edit nodes. It does
	 * this by checking if the user is a member of the group specified
	 * @see $gid
	 * @return boolean
	 */
	public function checkAccess()
	{
		// Bypass validation when in development mode
		if(defined('YII_DEVEL_MODE') && YII_DEVEL_MODE === true)
			return true;
		
		if(!is_numeric($this->gid))
			throw new CException("Cms.gid must be specified");
		
		return Yii::app()->user->hasGroup($this->gid);
	}

}