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
			'accessControl',
		);
	}
	
	/**
	 * Returns the access rules for this controller
	 * @return array
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'expression'=>'!Yii::app()->user->isGuest',
			),
			// Default rule
			array('deny')
		);
	}

	/**
	 * Creates a new vote
	 */
	public function actionCreate()
	{
		$model = new VoteForm();

		// Automatically set voter ID
		$model->voter = Yii::app()->user->getUserId();
		
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
				$vote->save(false);
				
				Yii::app()->user->setFlash('success', Yii::t('vote', 'Din röst har registrerats'));
				$this->redirect('results');
			}
		}
		
		// Get list of votable competitions whose deadline hasn't passed
		$competitions = Competition::model()->currentLan()->votable()
				->undueDeadline()->findAll();
		
		$this->render('create', array(
			'competitions'=>$competitions,
			'model'=>$model,
		));
	}

	/**
	 * AJAX-triggered action for fetching the submissions for the specified
	 * competition
	 */
	public function actionAjaxSubmissions()
	{
		if (isset($_POST['VoteForm']))
		{
			// Get the submissions
			$submissions = Submission::model()->findAllByAttributes(array(
				'competition_id'=>$_POST['VoteForm']['competition']));

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
					'placeholder'=>Yii::t('vote', 'Inga submissions hittades för denna tävling'),
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
		
		// Determine the scope to be used when fetching the list of 
		// competitions. Non-administrators can only see the results for 
		// competitions whose deadline has passed
		$modelScope = Competition::model()->currentLan()->votable();
		
		if (!Yii::app()->user->isAdmin())
			$modelScope = $modelScope->undueDeadline();

		$this->render('results', array(
			'model'=>$model,
			'competitions'=>$modelScope->findAll(),
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
			$competition = $this->loadModel($_POST['VoteResultForm']['competition']);
			
			// Get a data provider
			$dataProvider = new CActiveDataProvider('SubmissionVote', array(
				'criteria'=>array(
					'condition'=>'competition_id = :id',
					'params'=>array(':id'=>$competition->id),
				),
				'pagination'=>false,
			));
			
			$this->renderPartial('_resultList', array(
				'dataProvider'=>$dataProvider,
			));
		}
		
		Yii::app()->end();
	}
	
	/**
	 * Loads the specified model
	 * @param int $id the competition ID
	 * @return Competition the model
	 * @throws CHttpException if the model is not found
	 */
	private function loadModel($id)
	{
		$competition = Competition::model()->with('submissions')->findByPk($id);
		if ($competition === null)
			throw new CHttpException(400, Yii::t('vote', 'Ogiltig tävling'));

		return $competition;
	}

}