<?php

/**
 * Manages who is part of the committee (actually board)
 */
class CommitteeMemberController extends AdminController
{

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new CommitteeMember;

		if (isset($_POST['CommitteeMember']))
		{
			$model->attributes = $_POST['CommitteeMember'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Styrelsemedlemmen har lagts till');

				$this->redirect(array('admin'));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (isset($_POST['CommitteeMember']))
		{
			$model->attributes = $_POST['CommitteeMember'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Styrelsemedlemmen har uppdaterats');

				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model' => $model,
		));
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 *
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new CommitteeMember('search');
		$model->unsetAttributes();  // clear any default values

		if (isset($_GET['CommitteeMember']))
			$model->attributes = $_GET['CommitteeMember'];

		// Configure a data provider for the view
		$sort             = new CSort();
		$sort->attributes = array(
			'name' => array(
				'asc'  => 'user.name',
				'desc' => 'user.name DESC',
			),
			'year',
			'position',
		);

		$sort->defaultOrder = 'year DESC';

		$dataProvider       = $model->search();
		$dataProvider->sort = $sort;

		$this->render('admin', array(
			'model'        => $model,
			'dataProvider' => $dataProvider,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = CommitteeMember::model()->findByPk($id);

		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

}
