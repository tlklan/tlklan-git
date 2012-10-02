<?php

/**
 * Description of VoteController
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteController extends Controller
{

	public function actionCreate()
	{
		$currentLan = Lan::model()->getCurrent();
		$formModel = new VoteForm();

		// Get list of registrations and votable competitions
		$registrations = $currentLan->registrations;
		$competitions = Competition::model()->findAllByAttributes(array(
			'lan_id'=>$currentLan->id,
			'votable'=>1,
		));

		$this->render('create', array(
			'registrations'=>$registrations,
			'competitions'=>$competitions,
			'formModel'=>$formModel,
		));
	}

	public function actionAjaxSubmissions()
	{
		if(isset($_POST['VoteForm'])) 
		{
			// Get the submissions
			$submissions = Submission::model()->findAll('compo_id = :compo_id', array(
				':compo_id'=>$_POST['VoteForm']['competition'],
			));
			
			$data = CHtml::listData($submissions, 'id', 'name');
			
			foreach($data as $value => $name)
				echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name), true);
		}

		Yii::app()->end();
	}

}