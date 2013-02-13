<?php

/**
 * Handles backend-related tasks related to competitions
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionController extends AdminController
{

	/**
	 * Manages all competitions
	 */
	public function actionAdmin()
	{
		$model = new Competition('search');
		$model->unsetAttributes();  // clear any default values

		if (isset($_GET['Competition']))
			$model->attributes = $_GET['Competition'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}

	/**
	 * This action is called when the Update button is pressed from a 
	 * competition grid view. What it does is defined by the action parameter.
	 * @param string $action what should be done
	 * @throws CHttpException if the request was invalid
	 */
	public function actionAjaxUpdate($action)
	{
		if ($action == 'updateDisplayOrder' && isset($_POST['display_order']))
		{
			foreach ($_POST['display_order'] as $id=> $displayOrder)
			{
				if (!empty($displayOrder) && $displayOrder > 0)
				{
					$model = $this->loadModel($id);
					$model->display_order = $displayOrder;
					$model->save();
				}
			}

			Yii::app()->end();
		}

		throw new CHttpException(400, 'Invalid request');
	}

	/**
	 * Creates a new competition
	 */
	public function actionCreate()
	{
		$model = new Competition;

		if (isset($_POST['Competition']))
		{
			$model->attributes = $_POST['Competition'];
			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Tävlingen har skapats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a competition
	 * @param int $id the competition ID
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Updates a competition
	 * @param int $id the competition ID
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (isset($_POST['Competition']))
		{
			$model->attributes = $_POST['Competition'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Tävlingen har uppdaterats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Loads and returns a model
	 * @param int the ID of the model to be loaded
	 * @return Competition
	 */
	public function loadModel($id)
	{
		$model = Competition::model()->findByPk((int) $id);
		if ($model === null)
			throw new CHttpException(404, 'Unable to find competition');
		return $model;
	}

}