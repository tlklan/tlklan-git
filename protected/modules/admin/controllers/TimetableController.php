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
		
		if (isset($_POST['Timetable']))
		{
			$data = $_POST['Timetable'];
			
			// Keep track of errors and successful saves
			$successes = 0;
			$errors = array();

			foreach ($data as $id=> $attributes)
			{
				$model = $this->loadModel($id);
				$model->attributes = $attributes;

				if (!$model->validate())
					$errors = array_merge($errors, $model->getErrors());
				else
				{
					$model->save(false);
					++$successes;
				}
			}

			if ($errors)
			{
				// Convert the errors to a string
				$errorMessage = 'Vänligen korrigera följande fel:'.PHP_EOL.PHP_EOL;

				foreach ($errors as $messages)
					foreach ($messages as $message)
						$errorMessage .= $message.PHP_EOL;

				Yii::app()->user->setFlash('info', 'Endast '.$successes.' av '.count($data).' rader sparades korrekt');
				Yii::app()->user->setFlash('error', nl2br($errorMessage));
			}
			else
				Yii::app()->user->setFlash('success', 'Tidtabellen har uppdaterats');

			$this->refresh();
		}
		
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
		$model = $this->loadModel($id);
		if ($model !== null)
			$model->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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