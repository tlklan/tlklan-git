<?php

/**
 * Custom URL manager implementation. It adds the application language as a 
 * parameter to all created URLs so we can differentiate different versions of 
 * pages.
 */
class UrlManager extends CUrlManager
{

	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		// Don't overwrite an existing parameter
		if (!isset($params['language']))
		{
			// Use the current language specified by the URL parameter if possible
			if (isset($_GET['language']) && array_key_exists($_GET['language'], Controller::$validLanguages))
				$params['language'] = $_GET['language'];
			// Otherwise we use whatever has been set
			else
				$params['language'] = Yii::app()->language;
		}

		return parent::createUrl($route, $params, $ampersand);
	}

}