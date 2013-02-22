<?php

/**
 * Handles registrations (front-end part)
 */
class RegistrationController extends Controller
{

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'create';
	}

	/**
	 * Returns the filters defined for this controller
	 * @return array
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'checkLan + create',
			'checkEditPermissions + update, delete',
		);
	}

	/**
	 * Checks that there is a current LAN
	 * @throws CHttpException if no LAN has been enabled
	 * @param CFilterChain $filterChain the current filter chain
	 */
	public function filterCheckLan($filterChain)
	{
		if (Lan::model()->getCurrent() === null)
			throw new CHttpException(400, Yii::t('registration', 'Det går inte att anmäla sig till TLK LAN för tillfället. Kolla tillbaka om en stund!'));

		$filterChain->run();
	}

	/**
	 * Checks that the current user has the right to edit the registration. 
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if the registration can't be found or the user 
	 * doesn't have permission to edit it
	 */
	public function filterCheckEditPermissions($filterChain)
	{
		$registration = $this->loadModel(Yii::app()->request->getParam('id'));

		// Administrators should edit registrations from the backend so we only 
		// allow the owner to do it here
		if ($registration->user_id != Yii::app()->user->getUserId())
			throw new CHttpException(403, Yii::t('registration', 'Du kan inte ändra/ta bort någon annans anmälan'));

		$filterChain->run();
	}

	/**
	 * Returns the access rules for this controller
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create'),
			),
			// Only logged in users can update registrations
			array('allow',
				'actions'=>array('update', 'delete'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			// Default rule
			array('deny')
		);
	}

	/**
	 * Creates a new registration
	 */
	public function actionCreate()
	{
		$currentLan = Lan::model()->with('registrations', 'competitions', 'registrations.user')->getCurrent();

		$model = new Registration();

		if (isset($_POST['Registration']))
		{
			$model->attributes = $_POST['Registration'];
			$model->user_id = Yii::app()->user->userId;
			$model->lan_id = $currentLan->id;
			$model->date = date('Y-m-d H:i:s');

			if ($model->save())
			{
				// Register the user to the competitions he signed up for
				if (!empty($model->competitionList))
					$this->saveCompetitions($model->competitionList, $model->primaryKey);

				Yii::app()->user->setFlash('success', Yii::t('registration', 'Du är nu registrerad till {lanName}!', array('{lanName}'=>$currentLan->name)));

				$this->refresh();
			}
		}

		// Inform guests that they have to log in
		if (Yii::app()->user->isGuest)
			Yii::app()->user->setFlash('info', Yii::t('registration', 'Du måste vara inloggad för att registrera dig. Har du inte ett konto är det bara att skapa ett!'));

		$this->render('create', array(
			'model'=>$model,
			'currentLan'=>$currentLan,
		));
	}

	/**
	 * Updates a registration
	 * @param int $id the registration to update
	 */
	public function actionUpdate($id)
	{
		// Populate the model
		$model = $this->loadModel($id);
		$model->penis_long_enough = 'yes';

		// Populate competitionList
		foreach ($model->competitions as $competition)
			$model->competitionList[] = $competition->competition_id;

		if (isset($_POST['Registration']))
		{
			$model->attributes = $_POST['Registration'];

			if ($model->save())
			{
				// Register the user to the competitions he signed up for
				if (!empty($model->competitionList))
					$this->saveCompetitions($model->competitionList, $model->primaryKey);

				Yii::app()->user->setFlash('success', Yii::t('registration', 'Anmälan ifråga har uppdaterats'));

				$this->redirect(array('create'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a registration
	 * @param int $id the registration to delete
	 */
	public function actionDelete($id)
	{
		// Find the registration and check that it exists
		$registration = $this->loadModel($id);

		// Note: the database should handle deleting related competition signups
		$registration->delete();

		Yii::app()->user->setFlash('success', Yii::t('registration', 'Anmälan har tagits bort'));

		$this->redirect($this->createUrl('registration/create'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Registration::model()->findByPk((int) $id);
		if ($model === null)
			throw new CHttpException(400, Yii::t('registration', 'Anmälan hittades inte'));
		return $model;
	}

	/**
	 * Takes a list of competition ID and a registration ID and registers the 
	 * user to those competitions
	 * @param array $competitions array of integers
	 */
	private function saveCompetitions($competitions, $registrationId)
	{
		foreach ($competitions as $competitionId)
		{
			$competitor = new Competitor;
			$competitor->competition_id = $competitionId;
			$competitor->registration_id = $registrationId;
			$competitor->save();
		}
	}

}
