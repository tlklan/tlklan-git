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
			array('competition', 'validateCompetition'),
		);
	}
	
	/**
	 * Validates the competition attribute. It checks that the deadline hasn't 
	 * passed. This shouldn't happen unless someone modifies the POST data.
	 * @param string $attribute the attribute being validated
	 */
	public function validateCompetition($attribute)
	{
		$competition = Competition::model()->find('id = :id AND deadline >= NOW()', array(
			':id'=>$this->competition,
		));

		if ($competition === null)
			$this->addError($attribute, 'Du kan inte längre anmäla dig till den här tävlingen');
	}
	
	/**
	 * Validates the registration attribute. It checks that the user hasn't 
	 * already registered to the specified competition
	 * @param string $attribute the attribute being validated
	 */
	public function validateRegistration()
	{
		$competitor = ActualCompetitor::model()->findByAttributes(array(
			'registration_id'=>$this->registration,
			'competition_id'=>$this->competition,
		));

		if ($competitor !== null)
			$this->addError('competition', 'Du har redan anmält dig till denna tävling');
	}

}