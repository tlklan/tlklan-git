<?php

/**
 * Description of CompetitionRegistrationForm
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionRegistrationForm extends CFormModel
{

	public $registration;
	public $competition;

	public function attributeLabels()
	{
		return array(
			'registration'=>'Ditt nick',
			'competition'=>'Tävling',
		);
	}

	public function rules()
	{
		return array(
			array('registration, competition', 'required'),
			array('registration, competition', 'numerical', 'integerOnly'=>true),
			array('registration', 'validateRegistration'),
		);
	}
	
	public function validateRegistration($attribute)
	{
		$competitor = ActualCompetitor::model()->findByAttributes(array(
			'registration_id'=>$this->registration,
			'competition_id'=>$this->competition,
		));

		if ($competitor !== null)
			$this->addError($attribute, 'Du har redan anmält dig till denna tävling');
	}

}