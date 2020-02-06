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
	 * Prints a JSON object array for the upcoming events of the current date. 
	 * @param int $limit the amount of events to display
	 */
	public function actionGetUpcoming($limit = 5)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('lan_id = :lanId');
		$criteria->addCondition('start_time >= NOW()');
		$criteria->addCondition(('date = CURDATE()'));
		$criteria->params = array(':lanId'=>Lan::model()->getCurrent()->id);
		$criteria->limit = (int)$limit;
		$events = Timetable::model()->findAll($criteria);

		$jsonObjects = array();

		foreach ($events as $event)
			$jsonObjects[] = $event->attributes;

		header('Content-type: application/json');
		echo CJSON::encode($jsonObjects);
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