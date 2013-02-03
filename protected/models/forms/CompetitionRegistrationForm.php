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
			'competition'=>Yii::t('competition', 'Tävling'),
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
	 * Additionally it checks that the LAN has officially started.
	 * @param string $attribute the attribute being validated
	 */
	public function validateCompetition($attribute)
	{
		$competition = Competition::model()->undueDeadline()
				->findByPk($this->competition);

		if ($competition === null)
			$this->addError($attribute, Yii::t('competition', 'Du kan inte längre anmäla dig till den här tävlingen'));
		
		if (!$competition->lan->hasStarted())
		{
			$this->addError($attribute, Yii::t('competition', 'Du kan anmäla dig till tävlingar först då {lanName} har börjat'), array(
				'{lanName}'=>$competition->lan->name));
		}
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
			$this->addError('competition', Yii::t('competition', 'Du har redan anmält dig till denna tävling'));
	}

}