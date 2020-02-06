<?php

/**
 * This is the model class for table "tlk_registrations".
 *
 * The followings are the available columns in table 'tlk_registrations':
 * @property integer $id
 * @property integer $lan_id
 * @property integer $user_id
 * @property string $device
 * @property string $date
 * @property int $never_showed
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Competitions[] $competitions
 * @property Lan $lan
 * @property Results[] $results
 * @property Vote[] $votes
 */
class Registration extends CActiveRecord
{

	/**
	 * @var string the device the person will use
	 */
	public $device = 'desktop';

	/**
	 * @var array list of competitions (IDs) the user will likely participate in
	 */
	public $competitionList;

	/**
	 * @var boolean legacy question
	 */
	public $penis_long_enough;
	
	/**
	 * @var string used for sorting grids when data is fetched through search()
	 */
	private $_name;
	
	/**
	 * @var string used for sorting grids when data is fetched through search()
	 */
	private $_email;
	
	/**
	 * @var string used for sorting grids when data is fetched through search()
	 */
	private $_nick;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Registration the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tlk_registrations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('lan_id, user_id, device, date', 'required'),
			array('lan_id, user_id, never_showed', 'numerical', 'integerOnly'=>true),
			
			// LAN cannot be full
			array('lan_id', 'checkParticipantCount', 'on'=>'insert'),
			
			// user's can only register once
			array('nick', 'validateDuplicates', 'on'=>'insert'),
			
			// sanity checks
			array('device', 'validateDevice'),
			array('penis_long_enough', 'validatePenis'),
			array('competitionList', 'validateCompetitionList'),
			
			// search rule
			array('lanName, user, name, email, nick', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Populates the competitionList attribute (used in forms to display 
	 * checkbox lists)
	 */
	protected function afterFind()
	{
		parent::afterFind();

		foreach ($this->competitions as $competition)
			$this->competitionList[] = $competition->competition_id;
	}

	/**
	 * Checks that the user hasn't already registered to the current LAN
	 * @param string $attribute the attribute being validated
	 */
	public function validateDuplicates($attribute)
	{
		$dupes = Registration::model()->currentLan()->findAllByAttributes(array(
			'user_id'=>Yii::app()->user->userId));

		if (count($dupes) > 0)
			$this->addError($attribute, Yii::t('registration', 'Du har redan registrerat dig till detta LAN'));
	}
	
	/**
	 * Checks that the current LAN is not full
	 * @param string $attribute the attribute being validated
	 */
	public function checkParticipantCount($attribute)
	{
		if (Lan::model()->getCurrent()->isFull())
			$this->addError($attribute, Yii::t('registration', 'Det går inte längre att anmäla sig till det här LANet'));
	}

	/**
	 * Validates the "device" property.
	 * @param string $attribute the attribute being validated
	 */
	public function validateDevice($attribute)
	{
		if (!in_array($this->device, Device::getValidDevices()))
			$this->addError($attribute, Yii::t('registration', 'Du får inte komma på LAN med den valda maskinen'));
	}

	/**
	 * Validates the "penis size" property
	 * @param string $attribute the attribute being validated
	 */
	public function validatePenis($attribute)
	{
		if (!$this->hasErrors() && $this->penis_long_enough != 'yes')
			$this->addError($attribute, Yii::t('registration', '{minimumPenisLength} inch penis or GTFO', array(
				'{minimumPenisLength}'=>Yii::app()->params['minimumPenisLength'])));
	}

	/**
	 * Checks that the selected competitions actually exist
	 * @param string $attribute the attribute being validated
	 */
	public function validateCompetitionList($attribute)
	{
		if (!empty($this->competitionList))
		{
			$validCompetitions = Lan::model()->getCurrent()->competitions;

			// Make an array of the competition IDs so we can compare to $this->competitions
			$validCompetitionIds = array();
			foreach ($validCompetitions as $validCompetition)
				$validCompetitionIds[] = $validCompetition->id;

			foreach ($this->competitionList as $competition)
			{
				if (!in_array($competition, $validCompetitionIds))
				{
					$this->addError($attribute, Yii::t('registration', 'Ditt val av tävlingar är ogiltigt'));

					break;
				}
			}
		}
	}

	/**
	 * Defines the default scope for this model
	 * 
	 * @return array the default scope 
	 */
	public function defaultScope()
	{
		return array(
			// Order newest first
			'order'=>$this->getTableAlias(false, false).'.id DESC',
		);
	}

	/**
	 * Returns the scopes for this model.
	 * @return array the scope definitions
	 */
	public function scopes()
	{
		return array(
			'currentLan'=>array(
				// TODO: Check how often getCurrent() is called, perhaps we should cache
				'condition'=>'lan_id = '.Lan::model()->getCurrent()->id,
			)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'competitions'=>array(self::HAS_MANY, 'Competitor', 'registration_id'),
			'lan'=>array(self::BELONGS_TO, 'Lan', 'lan_id'),
			'results'=>array(self::HAS_MANY, 'Results', 'reg_id'),
			'votes'=>array(self::HAS_MANY, 'Vote', 'voter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'lan_id'=>Yii::t('registration', 'LAN'),
			'user_id'=>Yii::t('registration', 'Användar-ID'),
			'lanName'=>Yii::t('registration', 'LAN'),
			'name'=>Yii::t('registration', 'Namn'),
			'email'=>Yii::t('registration', 'E-post'),
			'nick'=>Yii::t('registration', 'Nick'),
			'device'=>Yii::t('registration', 'Jag använder en'),
			'competitionList'=>Yii::t('registration', 'Jag tänker eventuellt delta i dessa tävlingar'),
			'date'=>Yii::t('registration', 'Anmälningsdatum'),
			'penis_long_enough'=>Yii::t('registration', 'Penis längre än {length}"?', array('{length}'=>Yii::app()->params['minimumPenisLength'])),
			'never_showed'=>Yii::t('registration', 'Dök aldrig upp'),
		);
	}
	
	/**
	 * Save the user's competition registrations as well
	 */
	protected function afterSave()
	{
		// Remove any previous registrations
		if (!$this->isNewRecord)
			foreach ($this->competitions as $competition)
				$competition->delete();

		// Add the new ones
		if (!empty($this->competitionList))
		{
			foreach ($this->competitionList as $competition)
			{
				$competitor = new Competitor;
				$competitor->competition_id = $competition;
				$competitor->registration_id = $this->id;
				$competitor->save();
			}
		}

		parent::afterSave();
	}

	/**
	 * Returns true if the user is using a laptop
	 * @return boolean 
	 */
	public function hasLaptop()
	{
		return $this->device == 'laptop';
	}

	/**
	 * Returns true if the user is using a desktop
	 * @return boolean 
	 */
	public function hasDesktop()
	{
		return $this->device == 'desktop';
	}
	
	/**
	 * Returns the first registration the specified user ever made
	 * @return Registration
	 */
	public function getFirstRegistration($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'user_id = :user_id';
		$criteria->order = 'lan_id ASC';
		$criteria->limit = 1;
		$criteria->params = array(':user_id'=>$userId);

		return self::model()->find($criteria);
	}
	
	/**
	 * Getter for _name. Provided for backward compatibility + sorting/filtering
	 * @return string
	 */
	public function getName()
	{
		if (!isset($this->_name) && $this->user !== null)
			$this->_name = $this->user->name;

		return $this->_name;
	}

	/**
	 * Getter for _email. Provided for backward compatibility + sorting/filtering
	 * @return string
	 */
	public function getEmail()
	{
		if (!isset($this->_email) && $this->user !== null)
			$this->_email = $this->user->email;

		return $this->_email;
	}

	/**
	 * Getter for _nick. Provided for backward compatibility + sorting/filtering
	 * @return string
	 */
	public function getNick()
	{
		if (!isset($this->_nick) && $this->user !== null)
			$this->_nick = $this->user->nick;

		return $this->_nick;
	}
	
	/**
	 * Setter for _name. Provided for backward compatibility + sorting/filtering
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->_name = $name;
	}

	/**
	 * Setter for _email. Provided for backward compatibility + sorting/filtering
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->_email = $email;
	}

	/**
	 * Setter for _nick. Provided for backward compatibility + sorting/filtering
	 * @param string $nick
	 */
	public function setNick($nick)
	{
		$this->_nick = $nick;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('lan', 'user');

		$criteria->compare($this->getTableAlias().'id', $this->id);
		$criteria->compare('lan_id', $this->lan_id, false);
		$criteria->compare('user.name', $this->name, true);
		$criteria->compare('user.email', $this->email, true);
		$criteria->compare('user.nick', $this->nick, true);
		$criteria->compare('device', $this->device, true);
		$criteria->compare('date', $this->date, true);

		return new CActiveDataProvider('Registration', array(
			'criteria'=>$criteria,
		));
	}

}