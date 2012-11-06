<?php

/**
 * Handles registrations
 */
class RegistrationController extends AdminController
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Registration;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if (isset($_POST['Registration']))
		{
			$model->attributes = $_POST['Registration'];
			if ($model->save())
				$this->redirect(array('view', 'id'=>$model->id));
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a registration.
	 * @param int $id the ID of the registration to be updated
	 */
	public function actionUpdate($id)
	{
		// Load the model and populate the form model with its values
		$registration = $this->loadModel($id);	
		$model = new AdminRegistrationForm();
		$model->populate($registration);

		// Get the current LAN
		$currentLan = Lan::model()->getCurrent();
		if ($currentLan === null)
			throw new CHttpException(400, "Inget LAN är aktivt för tillfället");

		// Handle input
		if (isset($_POST['AdminRegistrationForm']))
		{
			$model->attributes = $_POST['AdminRegistrationForm'];
			
			if ($model->validate())
			{
				$registration->name = $model->name;
				$registration->email = $model->email;
				$registration->nick = $model->nick;
				$registration->device = $model->device;

				// Save and store the primary key for the next query
				$registration->save();
				$registrationId = $registration->primaryKey;

				// If this is an update, remove all previous competitions so we 
				// don't get any duplicates
				// TODO: Remove this duplicate code (move to separate method)
				if (!$registration->isNewRecord)
					foreach ($registration->competitions as $competition)
						$competition->delete();

				// Register the user to the specifeid competitions if he signed 
				// up for any
				if (!empty($model->competitions))
				{
					foreach ($model->competitions as $competition)
					{
						$competitor = new Competitor;
						$competitor->competition_id = $competition;
						$competitor->registration_id = $registrationId;

						$competitor->save();
					}
				}

				Yii::app()->user->setFlash('success', 'Anmälan ifråga har uppdaterats');

				$this->redirect($this->createUrl('registration/admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
			'registration'=>$registration,
			'competitions'=>$currentLan->competitions,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('Registration');
		$this->render('index', array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all registrations
	 */
	public function actionAdmin()
	{
		$model = new Registration('search');
		$model->unsetAttributes();
		
		if (isset($_GET['Registration']))
			$model->attributes = $_GET['Registration'];

		// Configure a data provider for the view
		$sort = new CSort();
		$sort->attributes = array(
			'name',
			'email',
			'nick',
			'device',
			'date',
			// enable sorting by LAN
			'lanName'=>array(
				'asc'=>'lan.id',
				'desc'=>'lan.id DESC',
			)
		);
		
		$sort->defaultOrder = 'lan.id DESC';
		
		$dataProvider = $model->search();
		$dataProvider->sort = $sort;
		$dataProvider->pagination = array('pageSize'=>40);
		
		$this->render('admin', array(
			'dataProvider'=>$dataProvider,
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
		$model = Registration::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
