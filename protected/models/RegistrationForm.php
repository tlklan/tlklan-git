<?php

/**
 * Description of RegisterForm
 *
 * @author Sam
 */
class RegistrationForm extends CFormModel implements ArrayAccess {

	public $name;
	public $email;
	public $nick;
	public $device = 'desktop';
	public $competitions;
	public $penis_long_enough;

	/** 
	 * List of valid devices
	 * @var array
	 */
	private $_validDevices = array(
		'desktop',
		'laptop',
	);
	
	public function rules() {
		return array(
			array('name, email, nick, device', 'customValidator'),
			array('nick', 'validateDuplicates', 'on'=>'create'),
			array('email', 'email', 'message'=>'Din e-postadress är ogiltig'),
			array('device', 'deviceValidator'),
			array('penis_long_enough', 'penisValidator'),
			array('competitions', 'competitionValidator'),
		);
	}
	
	/**
	 * General validator. It loops through all properties of this model and 
	 * adds an error to it if any of the properties are empty
	 * 
	 * @param string $attribute not used
	 * @param string $params not used
	 */
	public function customValidator($attribute, $params) {
		// This is a hack to get all fields through the validator but still
		// adding only one error to the model
		// TODO: Do this in a cleaner way
		static $errors = false;
		
		foreach($this as $property => $value) {
			// Don't validate the "competitions" property, it is validated separately
			if($property != 'competitions' && empty($value)) {
				if($errors === false) {
					$this->addError($property, 'Du måste fylla i alla fält');
				}
				$errors = true;
			}
		}
	}
	
	/**
	 * Checks that the user hasn't already registered. The check is done either 
	 * by name, e-mail or nick.
	 * @param string $attribute the attribute being validated
	 */
	public function validateDuplicates($attribute)
	{
		$dupes = Registration::model()->currentLan()->findAll('(name = :name OR email = :email OR nick = :nick)', array(
			':name'=>$this->name,
			':email'=>$this->email,
			':nick'=>$this->nick,
		));
		
		if(count($dupes) > 0)
			$this->addError($attribute, "Du har redan registrerat dig till detta LAN");
	}
	
	/** 
	 * Validates the "device" property. It checks that the selected device 
	 * listed in $this->_validDevices 
	 * 
	 * @param string $attribute not used
	 * @param string $params not used
	 */
	public function deviceValidator($attribute, $params) {
		if(!in_array($this->device, $this->_validDevices)) {
			$this->addError('device', 'Du får inte komma på LAN med den valda maskinen.');
		}
	}
	
	/** 
	 * Validates the "penis size" property
	 * 
	 * @param string $attribute not used
	 * @param string $params not used
	 */
	public function penisValidator($attribute, $params) {
		if($this->penis_long_enough  == 'no') {
			$this->addError('penis_long_enough', Yii::app()->params['minimumPenisLength'].' inch penis or GTFO');
		}
	}

	/**
	 * Checks that the selected competitions are valid (ie. that they exist). 
	 * If none are selected then this method does nothing.
	 * 
	 * @param string $attribute not used
	 * @param string $params not used
	 */
	public function competitionValidator($attribute, $params)
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
					$this->addError('competition', 'Ditt val av tävlingar är ogiltigt');
		}
	}
	
	/**
	 * Populates this models properties with the corresponding ones from the 
	 * specified model. This is necessary because the form and the actual database 
	 * entry are different models.
	 * 
	 * @param Registration $model 
	 */
	public function populate($model) {
		$this->name = $model->name;
		$this->email = $model->email;
		$this->nick = $model->nick;
		$this->device = $model->device;
		$this->penis_long_enough = 'yes'; // This would always be yes
		
		// Loop through competitions and add their IDs
		foreach($model->competitions as $competition) {
			$this->competitions[] = $competition->competition_id;
		}
	}
	
	public function attributeLabels() {
		return array(
			'name' => 'Namn',
			'email' => 'E-post',
			'nick' => 'Nick',
			'device' => 'Jag använder en',
			'competitions' => 'Jag tänker delta<br />i dessa tävlingar',
			'penis_long_enough' => 'Penis längre än '.Yii::app()->params['minimumPenisLength'].'"?',
		);
	}
	
	/**
	 * Implements the ArrayAccess interface
	 */
	public function offsetExists($offset) {
		return isset($this->$offset);
	}

	/**
	 * Implements the ArrayAccess interface
	 */
	public function offsetGet($offset) {
		return $this->$offset;
	}

	/**
	 * Implements the ArrayAccess interface (not used)
	 */
	public function offsetSet($offset, $value) {
		
	}

	/**
	 * Implements the ArrayAccess interface (not used)
	 */
	public function offsetUnset($offset) {
		
	}

}