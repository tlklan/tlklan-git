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
				var_dump($model);
				exit;
			}
		}
		
		// Get list of registrations and votable competitions
		$registrations = $currentLan->registrations;
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
			// Get the submissions
			$submissions = Submission::model()->findAll('compo_id = :compo_id', array(
				':compo_id'=>$_POST['VoteForm']['competition'],
					));

			$data = CHtml::listData($submissions, 'id', 'name');

			// Render some checkboxes if there's anything to select
			if (count($data) > 0)
			{
				$model = new VoteForm();
				$form = $this->beginWidget('TbActiveForm', array(
					'type'=>'horizontal',
				));

				echo $form->checkBoxListRow($model, 'submissions', array_values($data));

				$this->endWidget();
			}
			else
			{
				$this->renderPartial('_submissionList', array(
					'placeholder'=>'Inga submissions hittades för denna tävling',
				));
			}
		}

		Yii::app()->end();
	}

}