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
	 * Sets the application language to the value stored in the session
	 */
	public function setTargetLanguage()
	{
		// Use Swedish if no target language has been set
		$targetLanguage = Yii::app()->session->get('targetLanguage', 'sv');

		Yii::app()->setLanguage($targetLanguage);
	}

}