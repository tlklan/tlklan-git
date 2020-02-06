<?php

/**
 * Default controller
 */
class DefaultController extends AdminController
{

	/**
	 * Default action. All it does is redirect to the dashboard
	 */
	public function actionIndex()
	{
		$this->redirect($this->createUrl('dashboard/'));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if (($error = Yii::app()->errorHandler->error))
		{
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

}