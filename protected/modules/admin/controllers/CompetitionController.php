<?php

/**
 * Handles backend-related tasks related to competitions
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionController extends AdminController
{

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
				// Don't set the order to something stupid
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