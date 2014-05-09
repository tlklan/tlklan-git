<?php

/**
 * Handles creation/updating of user accounts
 */
class UserController extends Controller
{

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'profile';
	}

	/**
	 * Returns the filters for this controller
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	/**
	 * Specifies the access control rules for this controller
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('register'),
			),
			array('allow',
				'actions'=>array('list', 'profile', 'update', 'changePassword'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			array('deny'),
		);
	}
	
	/**
	 * Shows a grid of all users
	 */
	public function actionList()
	{
		$dataProvider = User::model()->search();
		$dataProvider->pagination = false;
		$dataProvider->sort = array('defaultOrder'=>'name ASC');

		$this->render('list', array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Registers a new user on the site (not the same as registering to a LAN)
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

				// Inform the committee that someone has registered
				// TODO: Re-enable once Composer has been used, this does not currently work
				//$this->sendRegistrationNotification($model);
				
				Yii::app()->user->setFlash('success', Yii::t('user', 'Du är nu registrerad'));
				
				// Login the user
				$identity = new UserIdentity($model->username, $model->newPassword);
				$identity->authenticate();
				Yii::app()->user->login($identity);

				$this->redirect(array('user/profile'));
			}
		}

		$this->render('register', array(
			'model'=>$model,
		));
	}

	/**
	 * Changes the password for the current user
	 */
	public function actionChangePassword()
	{
		$model = $this->loadModel(Yii::app()->user->getUserId());
		$model->scenario = 'changePassword';

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if ($model->validate())
			{
				// Hash the password
				$model->password = Yii::app()->hasher->hashPassword($model->newPassword);

				// We have already valited so no need to do it here
				if ($model->save(false))
				{
					Yii::app()->user->setFlash('success', Yii::t('user', 'Ditt lösenord har uppdaterats'));

					$this->redirect(array('profile'));
				}
			}
		}

		$this->render('changePassword', array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a user profile. If no ID parameter is passed, the currently 
	 * logged in user's profile is shown.
	 */
	public function actionProfile($id = null)
	{
		$userId = $id === null ? Yii::app()->user->getUserId() : $id;
		
		// Do some eager loading, it will be needed when determining the user's
		// badges
		$with = array(
			'submissions',
			'submissions.competition',
			'submissions.competition.lan',
			'submissionCount',
			'lans'=>array('scopes'=>array('notEnded')),
			'lanCount'=>array('scopes'=>array('notEnded')),
			'registrations',
		);

		$model = User::model()->with($with)->findByPk($userId);
		
		$this->render('profile', array(
			'model'=>$model,
			'wonCompetitions'=>$this->getAllWonCompetitions($model),
		));
	}

	/**
	 * Updates a the user's details
	 */
	public function actionUpdate()
	{
		// Load the current user's model
		$model = $this->loadModel(Yii::app()->user->getUserId());

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			$model->profileImage = CUploadedFile::getInstance($model, 'profileImage');
			$model->removeProfileImage = $_POST['User']['removeProfileImage'];

			if ($model->validate())
			{
				// Save eventual images
				if ($model->profileImage !== null)
				{
					// There can only be one...
					if ($model->image !== null)
						$model->image->delete();

					$image = Yii::app()->image->save($model->profileImage, $model->username);

					$model->image_id = $image->id;
				}
				
				// Remove the profile image if the user checked the box
				if ($model->removeProfileImage && $model->image !== null)
				{
					$model->image->delete();

					// This is necessary because Yii remembers the image_id 
					// which is not valid after the image was deleted
					$model->image_id = null;
				}
				
				$model->save(false);

				Yii::app()->user->setFlash('success', Yii::t('user', 'Dina användaruppgifter har uppdaterats'));

				$this->redirect(array('profile'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Returns all the competitions that the user has won (both those won by 
	 * skill and by vote)
	 * TODO: This is getting complicated
	 * @param User $user the user
	 * @return Competition[] the won competitions
	 */
	private function getAllWonCompetitions($user)
	{
		// Get the ActualCompetitor models for the competitions where the user 
		// won
		$with = array(
			'registration'=>array(
				'select'=>false,
				'condition'=>'registration.user_id = :user_id',
				'params'=>array(':user_id'=>$user->id)));

		$actualCompetitors = ActualCompetitor::model()->with($with)
				->findAllByAttributes(array('position'=>1));

		// Determine the user's winning submissions
		$wonCompetitionIds = array();
		$wonSubmissions = array();

		foreach ($user->getWonCompetitions() as $wonCompetition)
			$wonCompetitionIds[] = $wonCompetition->id;

		foreach ($user->submissions as $submission)
		{
			$competition = $submission->competition;

			// Don't count multiple submissions
			if (in_array($submission->competition->id, $wonCompetitionIds) &&
			   !in_array($competition, $wonSubmissions))
			{
				$wonSubmissions[] = $competition;
			}
		}

		// Combine both lists
		$wonCompetitions = array();

		foreach ($actualCompetitors as $actualCompetitor)
			$wonCompetitions[] = $actualCompetitor->competition;

		return array_merge($wonCompetitions, $wonSubmissions);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = User::model()->findbyPk($id);

		if ($model === null)
			throw new CHttpException(404, Yii::t('general', 'Sidan du sökte finns ej'));

		return $model;
	}
	
	/**
	 * Sends a notification e-mail to the committee informing them that someone 
	 * has made an account on the site.
	 * @param User $user the model for the new user
	 */
	private function sendRegistrationNotification($user)
	{
		// Import Zend_Mail
		Yii::import('application.vendors.*');

		require_once('Zend/Mail.php');
		require_once('Zend/Mail/Exception.php');

		// Construct the email
		$mail = new Zend_Mail('UTF-8');
		$mail->setFrom(Yii::app()->params['mail']['noreply']);
		$mail->addTo(Yii::app()->params['mail']['committee']);
		$mail->setSubject($user->email.' har registrerat sig på lan.tlk.fi');

		// The e-mail body is in a separate file for easier editing
		$body = $this->renderPartial('_registrationNotification', array(
			'user'=>$user), true);

		$mail->setBodyText($body);

		// Send the e-mail
		try
		{
			$mail->send();
		}
		catch (Zend_Mail_Exception $e)
		{
			// There's not much we can do at this point except log the error
			Yii::log('Failed to send notification e-mail: '.$e->getMessage(), CLogger::LEVEL_ERROR);
		}
	}

}
