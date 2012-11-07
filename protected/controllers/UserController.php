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
		$model = $this->loadModel();
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
	 * Creates a new user.
	 */
	public function actionCreate()
	{
		$model = new User;

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Displays the user's profile page
	 */
	public function actionProfile()
	{
		$this->render('profile', array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Updates a the user's details
	 */
	public function actionUpdate()
	{
		$model = $this->loadModel();

		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Dina användaruppgifter har uppdaterats');

				$this->redirect(array('profile'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
//	public function actionDelete($id)
//	{
//		$this->loadModel()->delete();
//
//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if (!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//	}

	/**
	 * Lists all models.
	 */
//	public function actionIndex()
//	{
//		$dataProvider = new CActiveDataProvider('User');
//		$this->render('index', array(
//			'dataProvider'=>$dataProvider,
//		));
//	}

	/**
	 * Manages all models.
	 */
//	public function actionAdmin()
//	{
//		$model = new User('search');
//		$model->unsetAttributes();  // clear any default values
//		if (isset($_GET['User']))
//			$model->attributes = $_GET['User'];
//
//		$this->render('admin', array(
//			'model'=>$model,
//		));
//	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel()
	{
		$model = User::model()->find('username = :username', array(
			':username'=>Yii::app()->user->nick,
				));

		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

}
