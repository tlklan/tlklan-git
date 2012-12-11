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
				'actions'=>array('profile', 'update', 'changePassword'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			array('deny'),
		);
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
					Yii::app()->user->setFlash('success', 'Ditt lösenord har uppdaterats');

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
		$model = $this->loadModel($userId);

		// Use different page titles when viewing others' profiles
		if ($id === null)
			$this->pageTitle = 'Din profil';
		else
			$this->pageTitle = $model->name.'s profil';

		$this->render('profile', array(
			'model'=>$model,
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

				Yii::app()->user->setFlash('success', 'Dina användaruppgifter har uppdaterats');

				$this->redirect(array('profile'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
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
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

}
