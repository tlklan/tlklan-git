<?php

/**
 * Form model for registering to competitions
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionRegistrationForm extends CFormModel
{

	/**
	 * @var int the registration ID
	 */
	public $registration;
	
	/**
	 * @var int the competition ID
	 */
	public $competition;

	/**
	 * Returns the attribute labels for this model
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'registration'=>'Ditt nick',
			'competition'=>'Tävling',
		);
	}

	/**
	 * Returns the validation rules for this model
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('registration, competition', 'required'),
			array('registration, competition', 'numerical', 'integerOnly'=>true),
			array('registration', 'validateRegistration'),
		);
	}
	
	/**
	 * Validates the registration attribute. It checks that the user hasn't 
	 * already registered to the specified competition
	 * @param string $attribute the attribute being validated
	 */
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