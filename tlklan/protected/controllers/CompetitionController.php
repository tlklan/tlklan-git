<?php

/**
 * Handles registering to and deleting competitions
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionController extends Controller
{

	/**
	 * Returns the filters defined for this controller
	 * @return type
	 */
	public function filters()
	{
		return array(
			'ajaxOnly + delete',
			'accessControl',
		);
	}
	
	/**
	 * Returns the access rules for this model
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('register'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			// Only administrators can delete competitors
			array('allow',
				'actions'=>array('delete'),
				'expression'=>'$user->isAdmin()',
			),
			// Default rule
			array('deny')
		);
	}
	
	/**
	 * Registers a new competitor
	 */
	public function actionRegister()
	{
		$model = new CompetitionRegistrationForm();

		// Find the registration ID for the user
		$registration = Registration::model()->currentLan()->findByAttributes(array(
			'user_id'=>Yii::app()->user->getUserId()));
		
		// Don't allow registration if the user is not on the LAN
		if($registration === null)
			throw new CHttpException(403, Yii::t('competition', 'Du måste vara anmäld till LANet för att kunna anmäla dig till tävlingar'));
		else
			$model->registration = $registration->id;
		
		// Handle form input
		if (isset($_POST['CompetitionRegistrationForm']))
		{
			$model->attributes = $_POST['CompetitionRegistrationForm'];

			if ($model->validate())
			{
				$competitor = new ActualCompetitor();
				$competitor->competition_id = $model->competition;
				$competitor->registration_id = $model->registration;
				$competitor->save(false);

				Yii::app()->user->setFlash('success', Yii::t('competition', 'Din anmälan har registrerats'));
				$this->redirect('register');
			}
		}

		$competitions = Competition::model()->currentLan()->signupable()->findAll();

		$this->render('create', array(
			'model'=>$model,
			'competitions'=>$competitions,
		));
	}
	
	/**
	 * Deletes the specified model
	 * @param int $id the competitior ID
	 */
	public function actionDelete($id)
	{
		$competitor = ActualCompetitor::model()->findByPk($id);
		if ($competitor !== null)
			$competitor->delete();
	}

}