<?php

/**
 * Subclass of CUrlManager. It adds the application language as a parameter to 
 * all created URLs so we can differentiate different versions of pages.
 */
class UrlManager extends CUrlManager
{

	public function createUrl($route, $params = array(), $ampersand = '&')
	{
		// Don't do this inside modules
		if (strpos($route, 'admin') !== 0)
		{
			// The language is stored in the session so we can grab it from there 
			// if the user fiddles with the URL
			if (!isset($params['language']))
				$params['language'] = Yii::app()->language;
		}

		return parent::createUrl($route, $params, $ampersand);
	}
	
	

}