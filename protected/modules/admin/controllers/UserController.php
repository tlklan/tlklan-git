<?php

/**
 * Handles browsing/updating/deleting users
 */
class UserController extends AdminController
{

	/**
	 * Updates a particular user
	 * @param int $id the user ID
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$model->scenario = 'update-admin';
		
		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			if ($model->validate())
			{
				// Optionally update the password
				if(!empty($model->newPassword))
					$model->password = Yii::app()->hasher->hashPassword($model->newPassword);
				
				$model->save(false);

				Yii::app()->user->setFlash('success', 'Användaren har uppdaterats');
				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular user
	 * @param int $id the user ID
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), 
		// we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all users.
	 */
	public function actionAdmin()
	{
		$model = new User('search');
		$model->unsetAttributes();

		if (isset($_GET['User']))
			$model->attributes = $_GET['User'];

		$this->render('admin', array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = User::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

}