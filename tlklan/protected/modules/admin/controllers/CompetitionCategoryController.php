<?php

/**
 * CRUD stuff for competition categories. Nothing special here whatsoever.
 */
class CompetitionCategoryController extends AdminController
{

	/**
	 * Creates a new model
	 */
	public function actionCreate()
	{
		$model = new CompetitionCategory;

		if (isset($_POST['CompetitionCategory']))
		{
			$model->attributes = $_POST['CompetitionCategory'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Kategorin har skapats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model
	 * @param int $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (isset($_POST['CompetitionCategory']))
		{
			$model->attributes = $_POST['CompetitionCategory'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Kategorin har uppdaterats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param int $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if (Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new CompetitionCategory('search');
		$model->unsetAttributes();

		if (isset($_GET['CompetitionCategory']))
			$model->attributes = $_GET['CompetitionCategory'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param id the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = CompetitionCategory::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

}
