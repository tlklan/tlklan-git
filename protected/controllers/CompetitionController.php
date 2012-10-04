<?php

/**
 * Description of CompetitionController
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionController extends Controller
{

	public function actionRegister()
	{
		$model = new CompetitionRegistrationForm();

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

		// Get list of registrations and votable competitions
		$currentLan = Lan::model()->getCurrent();

		$criteria = new CDbCriteria();
		$criteria->condition = 'lan_id = :lan_id';
		$criteria->order = 'nick ASC';
		$criteria->params = array(':lan_id'=>$currentLan->id);
		$registrations = Registration::model()->findAll($criteria);

		$competitions = Competition::model()->findAllByAttributes(array(
			'lan_id'=>$currentLan->id,
			'signupable'=>1,
		));

		$this->render('create', array(
			'model'=>$model,
			'registrations'=>$registrations,
			'competitions'=>$competitions,
		));
	}
	
	public function actionDelete($id)
	{
		$competitor = ActualCompetitor::model()->findByPk($id);
		if ($competitor !== null)
			$competitor->delete();
	}

}