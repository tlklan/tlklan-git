<?php

/**
 * Handles editing the timetable
 * 
 * TODO: Allow user to select which LAN's timetable to edit
 * TODO: Add "copy from previous LAN" feature
 *
 * @author Sam
 */
class TimetableController extends Controller
{

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'view';
	}

	/**
	 * Shows the timetable for the current LAN
	 */
	public function actionView()
	{
		$lan = Lan::model()->getCurrent();
		$dates = $this->getDateTimes($lan);

		$this->render('view', array(
			'lan'=>$lan,
			'dates'=>$dates,
		));
	}
	
	/**
	 * Loads and returns the specified model
	 * @param int the ID of the model to be loaded
	 * @return Timetable
	 */
	public function loadModel($id)
	{
		$model = Timetable::model()->findByPk((int)$id);
		if ($model === null)
			throw new CHttpException(404, 'Unable to find the event');
		return $model;
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