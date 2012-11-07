<?php

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
		);
	}

	/**
	 * Returns the access rules for this controller
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create', 'update'),
			),
			// Only administrators can delete registrations
			array('allow',
				'actions'=>array('delete'),
				'expression'=>'$user->isAdmin()',
			),
			// Default rule
			array('deny')
		);
	}
	
	/**
	 * Creates a new registration
	 * @param Registration $registration 
	 */
	public function actionCreate($registration = null) {
		// Get the model for the current LAN and abort if no LAN is currently 
		// set as active
		$currentLan = Lan::model()->getCurrent();
		if($currentLan === null)
			throw new CHttpException(400, "Det går inte att anmäla sig till TLK LAN för tillfället. Kolla tillbaka om en stund!");
		
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

			// Don't do anything if the LAN is already full booked
			// TODO: Move isFull validation to the form model
			if(!$currentLan->isFull()) {
				if($model->validate()) {
					// If this is a new registration we need to remember it
					$isNewRecord = $registration->isNewRecord;

					$registration->name = $model->name;
					$registration->email = $model->email;
					$registration->nick = $model->nick;
					$registration->device = $model->device;

					$registration->date = date('Y-m-d H:i:s');
					$registration->lan_id = $currentLan->id;

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
			else {
				Yii::app()->user->setFlash('error', 'Det går inte längre att anmäla sig till det här LANet');
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
	public function actionUpdate($id) {
		// Find the registration and check that it exists
		$registration = $this->loadModel($id);
		if($registration === null)
			throw new CHttpException(400, 'Anmälan hittades inte');
		
		// If the user is not an admin we check that he's not trying to edit 
		// someone else's registration
		// TODO: Move this logic to the accessControl filter
		if(!Yii::app()->user->isAdmin() && strtolower($registration->nick) != strtolower(Yii::app()->user->nick))
			throw new CHttpException(400, 'Du kan inte ändra någon annans anmälan');
		
		// Pass on the model to the create action which handles the rest of the magic
		$this->actionCreate($registration);
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
			throw new CHttpException(404, Yii::t('General', 'The requested page does not exist.'));
		return $model;
	}
}
