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
			throw new CHttpException(400, "Det går inte att anmäla sig till TLK LAN för tillfället. Kolla tillbaka om en stund!");

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

		if ($registration === null)
			throw new CHttpException(400, 'Anmälan hittades inte');

		// Administrators should edit registrations from the backend so we only 
		// allow the owner to do it here
		if ($registration->user_id != Yii::app()->user->getUserId())
			throw new CHttpException(403, 'Du kan inte ändra/ta bort någon annans anmälan');

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
	 * @param Registration $registration 
	 */
	public function actionCreate($registration = null) 
	{
		$currentLan = Lan::model()->with('registrations', 'competitions', 
				'registrations.user')->getCurrent();
		
		$model = new RegistrationForm;
		
		// Populate the form model with values from the registration model 
		// (if one is specified) and set scenarios.
		if($registration === null) {
			$registration = new Registration();
			$model->scenario = 'create';
		}
		else {
			$model->populate($registration);
			$model->scenario = 'update';
		}
		
		if(isset($_POST['RegistrationForm'])) {
			$model->attributes = $_POST['RegistrationForm'];

			if ($registration->isNewRecord)
				$registration->user_id = Yii::app()->user->userId;
			
			if($model->validate()) {
				// If this is a new registration we need to remember it
				$isNewRecord = $registration->isNewRecord;

				$registration->lan_id = $currentLan->id;
				$registration->device = $model->device;
				$registration->date = date('Y-m-d H:i:s');
				
				// Save and store the primary key for the next query
				$registration->save();
				$registrationId = $registration->primaryKey;

				// If this is an update, remove all previous competitions so we 
				// don't get any duplicates
				if(!$registration->isNewRecord) {
					foreach($registration->competitions as $competition) {
						$competition->delete();
					}
				}

				// Register the user to the specifeid competitions if he signed 
				// up for any
				if(!empty($model->competitions)) {
					foreach($model->competitions as $competition) {
						$competitor = new Competitor;
						$competitor->competition_id = $competition;
						$competitor->registration_id = $registrationId;

						$competitor->save();
					}
				}

				// Show different message depending on context
				if($isNewRecord)
					Yii::app()->user->setFlash('success', 'Du är nu registrerad till '.$currentLan->name.'!');
				else
					Yii::app()->user->setFlash('success', 'Anmälan ifråga har uppdaterats');

				// Redirect to the same action to prevent an F5 from
				// res-POSTing
				$this->redirect($this->createUrl('/registration/create'));
			}
		}
		
		$this->render('create', array(
			'model'=>$model,
			'registration'=>$registration,
			'currentLan'=>$currentLan,
			'competitions'=>$currentLan->competitions,
		));
	}
	
	/**
	 * Updates a registration
	 * @param int $id the registration to update
	 */
	public function actionUpdate($id)
	{
		// Pass on the model to the create action which handles the rest of the magic
		$this->actionCreate($this->loadModel($id));
	}
	
	/**
	 * Deletes a registration
	 * @param int $id the registration to delete
	 */
	public function actionDelete($id) {
		// Find the registration and check that it exists
		$registration = $this->loadModel($id);
		if($registration === null)
			throw new CHttpException(400, 'Anmälan hittades inte');
		
		// Note: the database should handle deleting related competition signups
		$registration->delete();
		
		Yii::app()->user->setFlash('success', 'Anmälan har tagits bort');
		
		$this->redirect($this->createUrl('registration/create'));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) {
		$model = Registration::model()->findByPk((int) $id);
		if($model === null)
			throw new CHttpException(404, Yii::t('general', 'Sidan du sökte finns ej'));
		return $model;
	}
}
