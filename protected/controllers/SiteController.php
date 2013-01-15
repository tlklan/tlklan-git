<?php

/**
 * Handles login/logout and some other things such as error pages and 
 * changing the application language
 */
class SiteController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'postOnly + changeLanguage',
		);
	}
	
	/**
	 * Changes the target language of the site
	 */
	public function actionChangeLanguage()
	{
		$previousLanguage = Yii::app()->language;
		
		// Use source language if the chosen language is invalid
		if (isset($_POST['language']))
		{
			$language = $_POST['language'];

			if (!array_key_exists($language, Controller::$validLanguages))
				$language = Yii::app()->sourceLanguage;
		}

		// Return the user to the place he came from
		$returnUrl = Yii::app()->request->getUrlReferrer();
		
		if ($returnUrl === null)
			$returnUrl = Yii::app()->homeUrl;
		else 
		{
			// Replace the language URL parameter
			$returnUrl = str_replace(Yii::app()->baseUrl.'/'.$previousLanguage, Yii::app()->baseUrl.'/'.$language, $returnUrl);
		}
		
		$this->redirect($returnUrl);
	}
	
	/**
	 * Default action. It redirects to the CMS page
	 */
	public function actionIndex()
	{
		$this->redirect(Yii::app()->cms->createUrl('home'));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if (($error = Yii::app()->errorHandler->error))
		{
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		if (isset($_POST['LoginForm']))
		{
			$model->attributes = $_POST['LoginForm'];

			// Use flash messages instead of errorSummary cause this model
			// is so simple
			if ($model->validate())
			{
				if ($model->login())
				{
					Yii::app()->user->setFlash('success', Yii::t('login', 'Du är nu inloggad'));

					// Redirect to the returnUrl we set earlier
					$this->redirect(Yii::app()->user->returnUrl);
				}
				else
					Yii::app()->user->setFlash('error', Yii::t('login', 'Felaktigt användarnamn eller lösenord'));
			}
		}

		$this->render('login', array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirects him to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(false); // Don't destroy the session
		Yii::app()->user->setFlash('success', Yii::t('login', 'Du har nu loggats ut'));

		$this->redirect(Yii::app()->homeUrl);
	}

}