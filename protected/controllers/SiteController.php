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
		// Don't do anything if the user tried to trick us somehow
		if (isset($_POST['language']))
		{
			$language = $_POST['language'];

			if (array_key_exists($language, Controller::$validLanguages))
				$_SESSION['targetLanguage'] = $_POST['language'];
		}

		// Return the user to the place he came from
		$returnUrl = Yii::app()->request->getUrlReferrer();
		if ($returnUrl === null)
			$returnUrl = Yii::app()->homeUrl;

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
					Yii::app()->user->setFlash('success', 'Du är nu inloggad');

					// Redirect to the returnUrl we set earlier
					$this->redirect(Yii::app()->user->returnUrl);
				}
				else
					Yii::app()->user->setFlash('error', 'Felaktigt användarnamn eller lösenord');
			}
		}

		$this->render('login', array('model'=>$model));
	}

	/**
	 * Handles user registration (not the same as registering to a LAN)
	 */
	public function actionRegister()
	{
		$model = new User();

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			// Change scenario if "has werket" was checked
			if ($model->has_werket_login)
				$model->scenario = 'insert-has-werket';

			if ($model->validate())
			{
				// Hash the password
				$model->password = Yii::app()->hasher->hashPassword($model->newPassword);
				$model->save(false);

				Yii::app()->user->setFlash('success', 'Du är nu registrerad och kan logga in genom att klicka på <i>Logga in</i> i menyn');

				$this->redirect(Yii::app()->homeUrl);
			}
		}

		$this->render('register', array(
			'model'=>$model,
		));
	}

	/**
	 * Logs out the current user and redirects him to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(false); // Don't destroy the session
		Yii::app()->user->setFlash('success', 'Du har nu loggats ut');

		$this->redirect(Yii::app()->homeUrl);
	}

}