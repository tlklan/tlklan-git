<?php

/**
 * Handles translations of text snippets on the site
 * TODO: Clean this file up and add proper comments
 */
class TranslationController extends AdminController
{

	/**
	 * Initializes the controller
	 */
	public function init()
	{
		$this->defaultAction = 'translate';

		parent::init();
	}

	/**
	 * Action for translating the yii messages (See t() function.)
	 * @param string $targetLanguage the language to translate to
	 */
	public function actionTranslate()
	{
		// Set some defaults
		$model = new TranslationFilterForm();
		$model->targetLanguage = 'en';
		
		// Update the filter
		if (isset($_POST['TranslationFilterForm']))
		{
			$model->attributes = $_POST['TranslationFilterForm'];
			
			// Reset attributes if something is wrong
			if (!$model->validate())
			{
				$model->targetLanguage = 'en';
				$model->category = false;
			}
		}
		
		// Update the translations
		if (isset($_POST['messageSourceId']))
		{
			$targetLanguage = $_POST['targetLanguage'];
			
			for ($i = 0; $i < count($_POST['messageSourceId']); $i++)
			{
				$messageSourceId = $_POST['messageSourceId'][$i];
				$translation = $_POST['translation'][$i];

				// Check if we have new translation.
				if (trim($translation) == '')
					continue;

				// Try to fetch the message from the database.
				$message = Message::model()->findByAttributes(array(
					'id'=>$messageSourceId,
					'language'=>$targetLanguage)
				);

				// If not found, create a new model.
				if ($message === null)
				{
					$message = new Message();
					$message->id = $messageSourceId;
					$message->language = $targetLanguage;
				}

				// Update the translation.
				$message->translation = $translation;
				$message->save(false);
			}

			// Show a flash message to the user.
			Yii::app()->user->setFlash('success', 'Dina ändringar har sparats');
		}

		// Get the list of translatable messages based on the filter
		$staticModel = MessageSource::model()->inUse();
		
		// Filter out a single category if one has been specified
		if (!empty($model->category))
			$staticModel->filterCategory($model->category);
		
		$messageSourceList = $staticModel->findAll(array(
			'with'=>array(
				'translations'=>array(
					'scopes'=>array('filterLanguage'=>$model->targetLanguage)
				)
			)
		));
		
		$this->render('translate', array(
			'model'=>$model,
			'messageSourceList'=>$messageSourceList,
		));
	}

}