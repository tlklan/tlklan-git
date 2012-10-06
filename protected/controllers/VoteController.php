<?php

/**
 * Handles voting and displaying vote results
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteController extends Controller
{

	/**
	 * Returns the filters defined for this controller
	 * @return type
	 */
	public function filters()
	{
		return array(
			'ajaxOnly + ajaxSubmissions, ajaxResults',
		);
	}

	/**
	 * Creates a new vote
	 */
	public function actionCreate()
	{
		$currentLan = Lan::model()->getCurrent();
		$model = new VoteForm();

		// Handle form data
		if (isset($_POST['VoteForm']))
		{
			$model->attributes = $_POST['VoteForm'];
			
			if ($model->validate())
			{
				// Save the vote
				$vote = new Vote();
				$vote->voter_id = $model->voter;
				$vote->submission_id = $model->submission;
				$vote->compo_id = $model->competition;
				$vote->save(false);
				
				Yii::app()->user->setFlash('success', 'Din röst har registrerats');
				$this->redirect('results');
			}
		}
		
		// Get list of registrations and votable competitions
		$criteria = new CDbCriteria();
		$criteria->condition = 'lan_id = :lan_id';
		$criteria->order = 'nick ASC';
		$criteria->params = array(':lan_id'=>$currentLan->id);
		
		$registrations = Registration::model()->findAll($criteria);
		$competitions = Competition::model()->findAll('lan_id = :lan_id AND votable = 1 AND deadline >= NOW()', array(
			':lan_id'=>$currentLan->id,
		));
		
		$this->render('create', array(
			'registrations'=>$registrations,
			'competitions'=>$competitions,
			'model'=>$model,
		));
	}

	/**
	 * AJAX-triggered action for fetching the submissions for the specified
	 * competition
	 * @throws CHttpException if the competition ID is invalid
	 */
	public function actionAjaxSubmissions()
	{
		if (isset($_POST['VoteForm']))
		{
			// Sanity checks
			$competitionId = $_POST['VoteForm']['competition'];
			if(empty($competitionId))
				throw new CHttpException(400, 'Ogiltig tävling');
			
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
	
	/**
	 * Displays the results page
	 */
	public function actionResults()
	{
		$model = new VoteResultForm();
		$currentLan = Lan::model()->getCurrent();
		
		// Get a list of votable competitions
		$competitions = Competition::model()->findAll('lan_id = :lan_id AND votable = 1 AND deadline <= NOW()', array(
			':lan_id'=>$currentLan->id,
		));
		
		// For administrators we replace $allCompetitions with all regardless 
		// of deadline
		if(Yii::app()->user->isAdmin())
		{
			$competitions = Competition::model()->findAll('lan_id = :lan_id AND votable = 1', array(
				':lan_id'=>$currentLan->id,
			));
		}
		
		$this->render('results', array(
			'model'=>$model,
			'competitions'=>$competitions,
		));
	}
	
	/**
	 * AJAX-triggered action which fetches the voting results for the specified 
	 * competition
	 * @throws CHttpException if the competition ID is invalid
	 */
	public function actionAjaxResults()
	{
		if (isset($_POST['VoteResultForm']))
		{
			// Find the competition's submissions
			$competitionId = $_POST['VoteResultForm']['competition'];
			
			$competition = Competition::model()->findByPk($competitionId);
			if($competition === null)
				throw new CHttpException(400, 'Ogiltig tävling');
			
			$this->renderPartial('_resultList', array(
				'dataProvider'=>$competition->getSubmissionDataProvider(),
			));
		}
		
		Yii::app()->end();
	}

}