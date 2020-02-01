<?php

/**
 * Handles competition suggestions
 */
class SuggestionController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	/**
	 * Specifies the access control rules
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('create', 'update', 'upvoteSuggestion'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			array('allow',
				'actions'=>array('update', 'delete'),
				'expression'=>'$user->isAdmin()',
			),
			array('deny')
		);
	}

	/**
	 * Creates a new suggestion and displays the list of currently available 
	 * suggestions
	 */
	public function actionCreate()
	{
		$model = new Suggestion();

		if (isset($_POST['Suggestion']))
		{
			$model->attributes = $_POST['Suggestion'];
			$model->user_id = Yii::app()->user->getUserId();

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', Yii::t('suggest-competiton', 'Ditt förslag har tagits emot'));

				$this->redirect('create');
			}
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates the specified suggestion.
	 * @param int $id the suggestion
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		
		// Check whether the user has permission to edit the suggestion
		if (!$model->isOwner(Yii::app()->user->getUserId()))
			throw new CHttpException(403, Yii::t('suggest-competition', 'Du kan inte ändra någon annans förslag'));

		if (isset($_POST['Suggestion']))
		{
			$model->attributes = $_POST['Suggestion'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', Yii::t('suggest-competition', 'Förslaget har uppdaterats'));

				$this->redirect(array('create'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * @param int $id the suggestion
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
	 * Upvotes the specified suggestion
	 * @param int $id the suggestion ID
	 */
	public function actionUpvoteSuggestion($id)
	{
		$model = new SuggestionVote();
		$model->suggestion_id = $id;
		$model->user_id = Yii::app()->user->getUserId();

		// The model will not validate if the user has already voted for this 
		// suggestion
		if ($model->save())
			Yii::app()->user->setFlash('success', Yii::t('suggest-competition', 'Din röst har tagits emot'));
		else
			Yii::app()->user->setFlash('error', Yii::t('suggest-competition', 'Du har redan röstat på det här förslaget'));

		$this->redirect(array('create'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Suggestion::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, Yii::t('general', 'Sidan du sökte finns ej'));
		return $model;
	}

}
