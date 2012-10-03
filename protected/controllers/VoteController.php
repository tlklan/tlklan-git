<?php

/**
 * Description of VoteController
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteController extends Controller
{

	public function filters()
	{
		return array(
			'ajaxOnly + ajaxSubmissions',
		);
	}

	public function actionCreate()
	{
		$currentLan = Lan::model()->getCurrent();
		$model = new VoteForm();

		if (isset($_POST['VoteForm']))
		{
			$model->attributes = $_POST['VoteForm'];
			
			if($model->validate()) {
				
			}
		}
		
		// Get list of registrations and votable competitions
		$criteria = new CDbCriteria();
		$criteria->condition = 'lan_id = :lan_id';
		$criteria->order = 'nick ASC';
		$criteria->params = array(':lan_id'=>$currentLan->id);
		
		$registrations = Registration::model()->findAll($criteria);
		$competitions = Competition::model()->findAllByAttributes(array(
			'lan_id'=>$currentLan->id,
			'votable'=>1,
		));

		$this->render('create', array(
			'registrations'=>$registrations,
			'competitions'=>$competitions,
			'model'=>$model,
		));
	}

	public function actionAjaxSubmissions()
	{
		if (isset($_POST['VoteForm']))
		{
			// Sanity check
			$competitionId = $_POST['VoteForm']['competition'];
			if(empty($competitionId))
				throw new CHttpException(400, 'Ogiltig tävling');
			
			// More sanity checks
			$competition = Competition::model()->findByPk($competitionId);
			if($competition === null)
				throw new CHttpException(400, 'Ogiltig tävling');
			
			$submissions = $competition->submissions;

			// Render some checkboxes if there's anything to select, otherwise
			// render just the place holder
			if (count($submissions) > 0)
			{
				$this->renderPartial('_submissionList', array(
					'model'=>new VoteForm(),
					'data'=>CHtml::listData($submissions, 'id', 'name'),
				));
			}
			else
			{
				$this->renderPartial('_placeholder', array(
					'placeholder'=>'Inga submissions hittades för denna tävling',
				));
			}
		}

		Yii::app()->end();
	}

}