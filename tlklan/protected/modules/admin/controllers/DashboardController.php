<?php

/**
 * Displays the dashboard
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class DashboardController extends AdminController
{

	/**
	 * Displays the dashboard
	 */
	public function actionIndex()
	{
		$currentLan = Lan::model()->getCurrent();

		$registration = new Registration('search');
		$registration->lan_id = $currentLan->id;

		$this->render('index', array(
			'lan'=>$currentLan,
			'registrationModel'=>$registration,
			'competitionDataProvider'=>Competition::model()->search($currentLan->id),
		));
	}

}