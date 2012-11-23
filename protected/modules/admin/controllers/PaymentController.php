<?php

/**
 * Handles payments
 */
class PaymentController extends AdminController
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
	 * Adds a new payment
	 */
	public function actionCreate()
	{
		$model = new Payment;

		if (isset($_POST['Payment']))
		{
			$model->attributes = $_POST['Payment'];

			if ($model->save())
			{

				Yii::app()->user->setFlash('success', 'Betalningen har registrerats');

				$this->redirect(array('admin'));
			}
		}

		$lanListData = CHtml::listData(Lan::model()->findAll(), 'id', 'name');

		$this->render('create', array(
			'model'=>$model,
			'lanListData'=>$lanListData,
		));
	}

	/**
	 * Updates a payment
	 * @param int $id the payment ID
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Pre-fill username
		$user = User::model()->findByPk($model->user_id);
		$model->name = $user->name;

		if (isset($_POST['Payment']))
		{
			$model->attributes = $_POST['Payment'];

			if ($model->save())
			{

				Yii::app()->user->setFlash('success', 'Betalningen har registrerats');

				$this->redirect(array('admin'));
			}
		}

		$lanListData = CHtml::listData(Lan::model()->findAll(), 'id', 'name');

		$this->render('update', array(
			'model'=>$model,
			'lanListData'=>$lanListData,
		));
	}

	/**
	 * Deletes a payment
	 * @param int $id the payment ID
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
	 * Shows a list of all payments and allows the user to sort/filter/update 
	 * them.
	 */
	public function actionAdmin()
	{
		$model = new Payment('search');
		$model->unsetAttributes();  // clear any default values

		if (isset($_GET['Payment']))
			$model->attributes = $_GET['Payment'];

		// Configure a data provider for the view
		$sort = new CSort();
		$sort->attributes = array(
			'userName'=>array(
				'asc'=>'user.name',
				'desc'=>'user.name DESC',
			),
			'lanName'=>array(
				'asc'=>'lan.name',
				'desc'=>'lan.name DESC',
			),
			'seasonName'=>array(
				'asc'=>'season.name',
				'desc'=>'season.name DESC',
			),
			'type',
		);

		$dataProvider = $model->search();
		$dataProvider->sort = $sort;
		$dataProvider->pagination = array('pageSize'=>50);

		$this->render('admin', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Payment::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

}
