<?php

/**
 * Handles editing the timetable
 * 
 * TODO: Allow user to select which LAN's timetable to edit
 * TODO: Add "copy from previous LAN" feature
 *
 * @author Sam
 */
class TimetableController extends AdminController
{

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'admin';
	}

	/**
	 * Default action. It renders a grid of the timetable for the current LAN 
	 * which can be edited.
	 */
	public function actionAdmin()
	{
		$lan = Lan::model()->getCurrent();
		$model = new Timetable();
		$dates = $this->getDateTimes($lan);

		$this->render('admin', array(
			'lan'=>$lan,
			'model'=>$model,
			'dates'=>$dates,
		));
	}
	
	/**
	 * Adds an empty event row to the time table for the specified LAN and date 
	 * and redirects to the admin page.
	 * @param int $lanId the LAN ID
	 * @param string $date the date (YYYY-MM-DD format)
	 */
	public function actionAddEvent($lanId, $date)
	{
		$model = new Timetable();
		$model->lan_id = $lanId;
		$model->date = $date;
		$model->save();

		Yii::app()->user->setFlash('success', 'En ny rad har skapats');

		$this->redirect(array('admin'));
	}
	
	/**
	 * Deletes the specified model
	 * @param int $id
	 */
	public function actionDelete($id)
	{
		$model = Timetable::model()->findByPk($id);
		if ($model !== null)
			$model->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	/**
	 * Returns an array of DateTime objects, one for each date during which the 
	 * LAN is on.
	 * @param Lan $lan the LAN
	 * @return DateTime[] $dates the dates
	 */
	private function getDateTimes($lan)
	{
		$dates = array();

		$startDate = new DateTime($lan->start_date);
		$endDate = new DateTime($lan->end_date.' + 1 day');

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($startDate, $interval, $endDate);

		foreach ($period as $date)
			$dates[] = $date;

		return $dates;
	}

}