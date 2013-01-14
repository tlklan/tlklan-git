<?php

/**
 * Application behavior which checks for a preferred target language in a 
 * session variable and changes the application language if found
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class ApplicationLanguageBehavior extends CBehavior
{

	/**
	 * Returns the events and event handlers for this behavior
	 * @return array
	 */
	public function events()
	{
		$events = array();

		if ($this->owner instanceof CApplication)
			$events = array('onBeginRequest'=>'setTargetLanguage');

		return $events;
	}

	/**
	 * Changes the application language to whatever is specified in the URL
	 */
	public function setTargetLanguage()
	{
		// Parse the language parameter from the URL. $_GET is not available yet 
		// so we have to do it the hard way
		$language = substr(Yii::app()->getRequest()->getUrl(), strlen(Yii::app()->baseUrl) + 1, 2);

		// Use source language if the language parameter is invalid
		if ($language === false || !array_key_exists($language, Controller::$validLanguages))
			$language = Yii::app()->sourceLanguage;

		Yii::app()->setLanguage($language);
	}

}