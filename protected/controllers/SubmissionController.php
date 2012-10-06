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
		);
	}

	/**
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			// Everyone can create and view submissions
			array('allow',
				'actions'=>array('create', 'archive'),
			),
			// Only allow logged in users to download submissions
			array('allow',
				'actions'=>array('get'),
				'expression'=>'Yii::app()->user->isGuest === false',
			),
			// Only admins can remove submission
			array('allow',
				'actions'=>array('update', 'delete'),
				'expression'=>'Yii::app()->user->isAdmin()',
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

		$isNewRecord = $model === null;
		if ($model === null)
			$model = new Submission();

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
				}

				$model->save();

				// Redirect to avoid F5 re-submission
				if ($isNewRecord)
					Yii::app()->user->setFlash('success', 'Din submission har laddats upp');
				else
					Yii::app()->user->setFlash('success', 'Entryn har uppdaterats');

				$this->redirect($this->createUrl('/submission/archive'));
			}
		}
		else {
			// Auto-select the correct nick for logged in users when adding
			// new submissions
			if ($isNewRecord && !Yii::app()->user->isGuest)
			{
				$registration = Registration::model()->findByNick(Yii::app()->user->name);

				if ($registration !== null)
					$model->submitter_id = $registration->id;
			}
		}
		
		// Get an ordered list of registrations
		$criteria = new CDbCriteria();
		$criteria->condition = 'lan_id = :lan_id';
		$criteria->order = 'nick ASC';
		$criteria->params = array(':lan_id'=>$currentLan->id);
		
		$registrations = Registration::model()->findAll($criteria);
		
		$this->render('create', array(
			'model'=>$model,
			'competitions'=>$currentLan->competitions,
			'registrations'=>$registrations,
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
		$lans = Lan::model()->findAll();

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
		$mime = mime_content_type($physicalPath);
		$basename = basename($physicalPath);

		// Serve the file to the browser
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
