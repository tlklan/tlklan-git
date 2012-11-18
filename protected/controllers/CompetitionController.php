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
		$registration = Registration::model()->currentLan()->find('user_id = :user_id', array(
			':user_id'=>Yii::app()->user->getUserId(),
		));
		
		// Don't allow registration if the user is not on the LAN
		if($registration === null)
			throw new CHttpException(403, "Du måste vara anmäld till LANet för att kunna anmäla dig till tävlingar");
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

				Yii::app()->user->setFlash('success', 'Din anmälan har registrerats');
				$this->redirect('register');
			}
		}

		$currentLan = Lan::model()->getCurrent();

		// Get a list of competitions that are "signupable" and whose dead-line
		// hasn't passed. Also get a list all competitions regardless of 
		// deadline (for the results)
		$competitions = Competition::model()->findAll('lan_id = :lan_id AND signupable = 1 AND deadline >= NOW()', array(
			':lan_id'=>$currentLan->id,
		));
		
		$allCompetitions = Competition::model()->findAll('lan_id = :lan_id AND signupable = 1', array(
			':lan_id'=>$currentLan->id,
		));

		$this->render('create', array(
			'model'=>$model,
			'competitions'=>$competitions,
			'allCompetitions'=>$allCompetitions,
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