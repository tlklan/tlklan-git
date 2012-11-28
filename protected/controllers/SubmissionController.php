<?php

/**
 * Handles uploading of new submissions and displaying the archive
 */
class SubmissionController extends Controller
{

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		parent::init();

		$this->defaultAction = 'archive';
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			'checkRegistration + create',
			'ownershipCheck + update, delete',
		);
	}
	
	/**
	 * Checks that the user has registered to the current LAN. This is a 
	 * pre-requisite for some stuff that happens in actionCreate
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if the user is not registered to the current LAN
	 */
	public function filterCheckRegistration($filterChain)
	{
		$registration = Registration::model()->currentLan()
				->find('user_id = :user_id', array(':user_id'=>
			Yii::app()->user->getUserId()));

		if ($registration === null)
			throw new CHttpException(403, "Du måste vara registrerad till LANet för att kunna submitta entries");

		$filterChain->run();
	}
	
	/**
	 * Checks that the submissions specified by the "id" GET parameter is 
	 * owned by the currently logged in user, or the user is an administrator.
	 * @param CFilterChain $filterChain the filter chain
	 * @throws CHttpException if the user is not allowed to perform actions on 
	 * this submission
	 */
	public function filterOwnershipCheck($filterChain)
	{
		$model = $this->loadModel(Yii::app()->request->getParam('id'));

		if ($model !== null && Yii::app()->user->isAdmin() ||
				$model->user_id == Yii::app()->user->getUserId())
		{
			$filterChain->run();
		}
		else
			throw new CHttpException(403, "Du kan inte ändra på andras submissions");
	}

	/**
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			// Everyone can view the list of submissions
			array('allow',
				'actions'=>array('archive'),
			),
			// Logged in users can update, delete and download submissions 
			// (actual ownership check is done in separate filter)
			array('allow',
				'actions'=>array('get', 'create', 'update', 'delete'),
				'expression'=>'!Yii::app()->user->isGuest',
			),
			array('deny'),
		);
	}

	/**
	 * Creates a new submission or updates an existing one if $model is provided
	 * @param Submission $model model to update, null to create new
	 */
	public function actionCreate($model = null)
	{
		$currentLan = Lan::model()->getCurrent();

		if ($model === null)
		{
			$model = new Submission();
			$model->user_id = Yii::app()->user->getUserId();
		}

		// If the submission is to a LAN that is not the current one we need 
		// to change scenario
		if ($model->competition->lan_id != $currentLan->id)
			$model->scenario = 'update-old';

		if (isset($_POST['Submission']))
		{
			$model->attributes = $_POST['Submission'];
			$model->file = CUploadedFile::getInstance($model, 'file');

			if ($model->validate())
			{
				// The file field can be empty when updating an entry. If it 
				// isn't we upload the file and store its path
				if ($model->file !== null)
				{
					// Determine the physical path where the submission should be 
					// stored
					$physicalPath = Yii::app()->params['submissionPath'].
							DIRECTORY_SEPARATOR.$model->competition->short_name.
							DIRECTORY_SEPARATOR.$currentLan->name.
							DIRECTORY_SEPARATOR;

					// Create the path if it doesn't exist. Throw exception if it 
					// is not couldn't be created.
					if (!is_dir($physicalPath) && !mkdir($physicalPath, 0777, true))
						throw new CHttpException(500, 'Kunde inte spara din submission');

					$physicalPath = $physicalPath.$model->file->name;
				
					$model->file->saveAs($physicalPath);
					$model->physical_path = $physicalPath;
					$model->size = $model->file->getSize();
				}
				
				if ($model->isNewRecord)
				{
					// Ensure the user is registered to the competition to 
					// which he is submitting (some badges depend on it)
					$userId = Yii::app()->user->getUserId();
					$user = User::model()->findByPk($userId);

					$hasCompoRegistration = false;

					foreach ($user->competitions as $competition)
						if ($competition->id == $model->compo_id)
							$hasCompoRegistration = true;

					if (!$hasCompoRegistration)
					{
						$registration = Registration::model()->currentLan()
								->find('user_id = :user_id', array(':user_id'=>$userId));

						$competitor = new Competitor();
						$competitor->registration_id = $registration->id;
						$competitor->competition_id = $model->compo_id;
						$competitor->save(false);
					}

					Yii::app()->user->setFlash('success', 'Din submission har laddats upp');
				}
				else
					Yii::app()->user->setFlash('success', 'Entryn har uppdaterats');
				
				$model->save(false);

				$this->redirect($this->createUrl('/submission/archive'));
			}
		}
		else {
			// Auto-select the correct nick for logged in users when adding
			// new submissions
			if ($model->isNewRecord && !Yii::app()->user->isGuest)
				$model->user_id = Yii::app()->user->userId;
		}
		
		// Show different view depending on if this is a create or update
		$this->render($model->isNewRecord ? 'create' : 'update', array(
			'model'=>$model,
			'competitions'=>$currentLan->competitions,
		));
	}
	
	/**
	 * Updates an existing entry. The main logic is in the create action.
	 * @param int $id the submission to update
	 * @throws CHttpException if the model can't be found
	 */
	public function actionUpdate($id)
	{
		$this->actionCreate($this->loadModel($id));
	}

	/**
	 * Displays the submission archive
	 */
	public function actionArchive()
	{
		// Use eager-loading because we'll be needing all this stuff anyway
		$lans = Lan::model()->with('competitions', 'competitions.submissions', 
				'competitions.submissions.voteCount', 
				'competitions.submissions.submitter')->findAll();

		$this->render('archive', array(
			'lans'=>$lans,
		));
	}

	/**
	 * Retrieves a submission and serves it to the user as an attachment
	 * 
	 * @param integer $id the submission ID
	 */
	public function actionGet($id)
	{
		$submission = Submission::model()->findByPk($id);

		// Check that the submission exists and that the file is readable
		if ($submission === null || !is_readable($submission->physical_path))
			throw new CHttpException(404, 'Filen hittades inte');

		$physicalPath = $submission->physical_path;

		// Get the MIME type and basename of the file
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mime = $finfo->file($physicalPath);
		$basename = basename($physicalPath);

		// Serve the file to the browser
		if ($mime !== false)
			header("Content-type: $mime");
		
		header('Content-Disposition: attachment; filename="'.$basename.'"');
		header('Content-Length: '.$submission->getSize(false));

		readfile($physicalPath);
	}

	/**
	 * Deletes the specified submission.
	 * 
	 * @param int $id the submission ID
	 * @throws CHttpException if the entry could not be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		// Inform of the physical path in the flash message
		$physicalPath = $model->physical_path;
		if ($model->delete())
		{
			Yii::app()->user->setFlash('success', 'Entryn har tagits bort. Den finns dock kvar på servern i <strong>'.$physicalPath.'</strong>');

			$this->redirect($this->createUrl('/submission/archive'));
		}
		else
			throw new CHttpException(500, "Kunde inte ta bort entryn.");
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Submission::model()->findByPk((int) $id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');

		return $model;
	}

}
