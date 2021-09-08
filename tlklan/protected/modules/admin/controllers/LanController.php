<?php

/**
 * Handles creating/updating/deleting LANs
 */
class LanController extends AdminController
{
	
	/**
	 * Returns the filters defined for this controller
	 * @return array the filters
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'postOnly + delete',
		));
	}

	/**
	 * Creates a new LAN.
	 */
	public function actionCreate()
	{
		$model = new Lan;

		if (isset($_POST['Lan']))
		{
			$model->attributes = $_POST['Lan'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', $model->name.' har skapats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates the specified LAN.
	 * @param int $id the ID of the LAN
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		
		if (isset($_POST['Lan']))
		{
			$model->attributes = $_POST['Lan'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', $model->name.' har uppdaterats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
			'competitionDataProvider'=>Competition::model()->search($id),
		));
	}

	/**
	 * Deletes the specified LAN.
	 * @param int $id the LAN ID
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we 
		// should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new Lan('search');
		$model->unsetAttributes(); // clear any default values

		if (isset($_GET['Lan']))
			$model->attributes = $_GET['Lan'];

		// Configure a data provider for the view
		$sort = new CSort();
		$sort->attributes = array(
			'name',
			'reg_limit',
			'start_date',
			'end_date',
			'location',
			'enabled',
			// enable sorting by season
			'seasonId'=>array(
				'asc'=>'season.id',
				'desc'=>'season.id DESC',
			)
		);

		$dataProvider = $model->search();
		$dataProvider->sort = $sort;
		$dataProvider->pagination = false; // no pagination
		
		// Get filter data for the seasonId attribute
		$seasons = Season::model()->getDropdownListOptions();

		$this->render('admin', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
			'seasons'=>$seasons,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param int the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Lan::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('general', 'Sidan du sökte finns ej'));
		return $model;
	}
	
}