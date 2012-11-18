<?php

/**
 * Defines the registration form.
 *
 * @author Sam Stenvall <sam.stenvall@arcada.fi>
 */
class RegistrationForm extends CFormModel
{

	/**
	 * @var string the person's name
	 */
	public $name;

	/**
	 * @var string the person's email address
	 */
	public $email;

	/**
	 * @var string the person's nickname
	 */
	public $nick;

	/**
	 * @var string the device the person will use
	 */
	public $device = 'desktop';

	/**
	 * @var array list of competitions (IDs) the user will likely participate in
	 */
	public $competitions;

	/**
	 * @var boolean legacy question
	 */
	public $penis_long_enough;

	/**
	 * @var array list of valid devices
	 */
	private $_validDevices = array(
		'desktop',
		'laptop',
	);

	/**
	 * Returns the validation rules for this model
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, email, nick, device', 'required'),
			array('nick', 'validateDuplicates', 'on'=>'create'),
			array('email', 'email', 'message'=>'Din e-postadress är ogiltig'),
			array('device', 'validateDevice'),
			array('penis_long_enough', 'validatePenis'),
			array('competitions', 'validateCompetitions'),
		);
	}
	
	/**
	 * Returns the attribute labels for this model
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>'Namn',
			'email'=>'E-post',
			'nick'=>'Nick',
			'device'=>'Jag använder en',
			'competitions'=>'Jag tänker delta<br />i dessa tävlingar (slutlig allmän görs under LAN <a href="'.Yii::app()->controller->createUrl('/competition/register').'">härifrån</a>)',
			'penis_long_enough'=>'Penis längre än '.Yii::app()->params['minimumPenisLength'].'"?',
		);
	}
	
	/**
	 * Initializes the model. Default values for some attributes and 
	 * scenario are set here.
	 */
	public function init()
	{
		parent::init();

		$user = Yii::app()->user;
		/* @var $user WebUser */

		if (!$user->isGuest)
		{
			$this->name = $user->name;
			$this->email = $user->email;
			$this->nick = $user->nick;
		}
	}
	
	/**
	 * Pre-validation logic. Here we check that the current LAN isn't already 
	 * full. If it is we add a general error to the model (no attribute) and 
	 * stop validating.
	 * @return boolean whether to continue validation
	 */
	protected function beforeValidate()
	{
		parent::beforeValidate();
		
		if ($this->scenario == 'create' && Lan::model()->getCurrent()->isFull())
		{
			$this->addError(false, "Det går inte längre att anmäla sig till det här LANet");

			return false;
		}

		return true;
	}

	/**
	 * Checks that the user hasn't already registered to the current LAN
	 * @param string $attribute the attribute being validated
	 */
	public function validateDuplicates($attribute)
	{
		$dupes = Registration::model()->currentLan()->findAll('user_id = :id', array(
			':id'=>Yii::app()->user->userId,
		));

		if (count($dupes) > 0)
			$this->addError($attribute, "Du har redan registrerat dig till detta LAN");
	}

	/**
	 * Validates the "device" property. It checks that the selected device 
	 * listed in $this->_validDevices 
	 * @param string $attribute the attribute being validated
	 */
	public function validateDevice($attribute)
	{
		if (!in_array($this->device, $this->_validDevices))
			$this->addError($attribute, 'Du får inte komma på LAN med den valda maskinen.');
	}

	/**
	 * Validates the "penis size" property
	 * @param string $attribute the attribute being validated
	 */
	public function validatePenis($attribute)
	{
		if (!$this->hasErrors() && $this->penis_long_enough != 'yes')
			$this->addError($attribute, Yii::app()->params['minimumPenisLength'].' inch penis or GTFO');
	}

	/**
	 * Checks that the selected competitions are valid (ie. that they exist). 
	 * If none are selected then this method does nothing.
	 * @param string $attribute the attribute being validated
	 */
	public function validateCompetitions($attribute)
	{
		if (!empty($this->competitions))
		{
			$validCompetitions = Lan::model()->getCurrent()->competitions;

			// Make an array of the competition IDs so we can compare to $this->competitions
			$validCompetitionIds = array();
			foreach ($validCompetitions as $validCompetition)
				$validCompetitionIds[] = $validCompetition->id;

			foreach ($this->competitions as $competition)
				if (!in_array($competition, $validCompetitionIds))
					$this->addError($attribute, 'Ditt val av tävlingar är ogiltigt');
		}
	}

	/**
	 * Populates this models properties with the corresponding ones from the 
	 * specified model. This is necessary because the form and the actual database 
	 * entry are different models.
	 * 
	 * @param Registration $model 
	 */
	public function populate($model)
	{
		$this->name = $model->name;
		$this->email = $model->email;
		$this->nick = $model->nick;
		$this->device = $model->device;
		$this->penis_long_enough = 'yes'; // This would always be yes
		
		// Loop through competitions and add their IDs
		foreach ($model->competitions as $competition)
			$this->competitions[] = $competition->competition_id;
	}

}