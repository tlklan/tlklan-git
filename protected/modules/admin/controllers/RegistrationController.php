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
	 * Updates a registration.
	 * @param int $id the ID of the registration to be updated
	 */
	public function actionUpdate($id)
	{
		// Populate the model
		$model = $this->loadModel($id);
		$model->penis_long_enough = 'yes';

		// Populate competitionList
		// TODO: Do this in afterFind
		foreach ($model->competitions as $competition)
			$model->competitionList[] = $competition->competition_id;
		
		// Get the current LAN
		$currentLan = Lan::model()->getCurrent();
		if ($currentLan === null)
			throw new CHttpException(400, "Inget LAN är aktivt för tillfället");

		// Handle input
		if (isset($_POST['AdminRegistration']))
		{
			$model->attributes = $_POST['AdminRegistration'];
			
			if ($model->save())
			{
				$registrationId = $model->primaryKey;
				
				// Register the user to the specifeid competitions if he signed 
				// up for any
				// TODO: Do in afterSave()
				if (!empty($model->competitionList))
				{
					foreach ($model->competitionList as $competition)
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
			'device',
			'date',
			'lanName'=>array(
				'asc'=>'lan.id',
				'desc'=>'lan.id DESC',
			),
			'name'=>array(
				'asc'=>'user.name',
				'desc'=>'user.name DESC',
			),
			'email'=>array(
				'asc'=>'user.email',
				'desc'=>'user.email DESC',
			),
			'nick'=>array(
				'asc'=>'user.nick',
				'desc'=>'user.nick DESC',
			),
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
		$model = AdminRegistration::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('general', 'Sidan du sökte finns ej'));
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
