<?php

/**
 * This is the model class for table "tlk_payments".
 *
 * The followings are the available columns in table 'tlk_payments':
 * @property integer $id
 * @property integer $user_id
 * @property integer $lan_id
 * @property integer $season_id
 * @property string $type
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Lan $lan
 * @property Season $season
 */
class Payment extends CActiveRecord
{
	
	/**
	 * Database identifier for payments for a single LAN only
	 */
	const TYPE_SINGLE = 'single';

	/**
	 * Database identifier for payments for a whole season
	 */
	const TYPE_SEASON = 'season';
	
	/**
	 * @var string the user's name
	 */
	public $name;

	/**
	 * @var string the user's name (used when sorting/filtering grid views)
	 */
	private $_userName;

	/**
	 * @var string the LAN name (used when sorting/filtering grid views)
	 */
	private $_lanName;

	/**
	 * @var string the season's name (used when sorting/filtering grid views)
	 */
	private $_seasonName;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Payment the static model class
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
		return 'tlk_payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, name, lan_id, type', 'required'),
			array('user_id, lan_id, season_id', 'numerical', 'integerOnly'=>true),
			array('user_id', 'validateUser'),
			array('user_id', 'validateDuplicates', 'on'=>'insert'),
			array('type', 'validateType'),
			array('season_id', 'validateSeason'),
			array('type', 'length', 'max'=>6),
			array('id, userName, lanName, seasonName, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'lan'=>array(self::BELONGS_TO, 'Lan', 'lan_id'),
			'season'=>array(self::BELONGS_TO, 'Season', 'season_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'user_id'=>'Användare',
			'userName'=>'Användare',
			'name'=>'Användare',
			'lan_id'=>'LAN',
			'lanName'=>'LAN',
			'season_id'=>'Säsong',
			'seasonName'=>'Säsong',
			'type'=>'Betalningstyp',
		);
	}
	
	/**
	 * Runs before validation. It fetches the actual user_id of the user. If 
	 * not found it sets it to 0 so we don't get a "user can't be empty" 
	 * message instead of the real error.
	 * @return boolean whether to continue validation
	 */
	protected function beforeValidate()
	{
		// Find the actual user_id
		$user = User::model()->findByAttributes(array('name'=>$this->name));
		$this->user_id = $user !== null ? $user->id : 0;

		return parent::beforeValidate();
	}
	
	/**
	 * Checks that the user hasn't already paid for the current season
	 * @param string $attribute the attribute being validated
	 */
	public function validateDuplicates($attribute)
	{
		// Find the season_id for the selected LAN
		$lan = Lan::model()->findByPk($this->lan_id);
		if ($lan === null)
			return;

		// See if the user has a payment for that season
		$payment = Payment::model()->findByAttributes(array(
			'user_id'=>$this->user_id, 'season_id'=>$lan->season_id));

		if ($payment !== null)
			$this->addError($attribute, 'Användaren har redan betalat');
	}
	
	/**
	 * Validates the user. It simply checks that the user exists.
	 * @param string $attribute the attribute being validated
	 */
	public function validateUser($attribute)
	{
		// Check that the user exists (the database refuses to save with invalid 
		// user_id anyway but we want a nicer error)
		$user = User::model()->findByPk($this->user_id);
		if ($user === null)
			$this->addError($attribute, 'Okänd användare');
	}
	
	/**
	 * Checks that a season is selected if payment type is set to "season"
	 * @param string $attribute the attribute being validated
	 */
	public function validateSeason($attribute)
	{
		$value = $this->{$attribute};

		if (empty($value) && $this->type == Payment::TYPE_SEASON)
			$this->addError($attribute, 'Du måste välja säsong');
		elseif ($value != '' && $this->type == Payment::TYPE_SINGLE)
			$this->season_id = null;
	}

	/**
	 * Checks that the payment type selected is among the valid options
	 * @param string $attribute the attribute being validated
	 */
	public function validateType($attribute)
	{
		if (!array_key_exists($this->type, $this->getValidTypes()))
			$this->addError($attribute, 'Okänd betalningstyp');
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('user', 'lan', 'season');
		
		$criteria->compare('id', $this->id);
		$criteria->compare('user.name', $this->getUserName(), true);
		$criteria->compare('lan.name', $this->getLanName(), true);
		$criteria->compare('season.name', $this->getSeasonName(), true);
		$criteria->compare('type', $this->type, true);

		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria));
	}
	
	/**
	 * Returns the user-friendly name of the payment type
	 * @return string
	 */
	public function getType()
	{
		$types = $this->getValidTypes();

		return $types[$this->type];
	}

	/**
	 * Returns an array of the valid payment types
	 * @return array
	 */
	public function getValidTypes()
	{
		return array(
			self::TYPE_SINGLE=>'Ett LAN',
			self::TYPE_SEASON=>'Säsong',
		);
	}

	/**
	 * Getter for _userName
	 * @return string
	 */
	public function getUserName()
	{
		if (!isset($this->_userName) && $this->user !== null)
			$this->_userName = $this->user->name;

		return $this->_userName;
	}

	/**
	 * Getter for _lanName
	 * @return string
	 */
	public function getLanName()
	{
		if (!isset($this->_lanName) && $this->lan !== null)
			$this->_lanName = $this->lan->name;

		return $this->_lanName;
	}

	/**
	 * Getter for _seasonName
	 * @return string
	 */
	public function getSeasonName()
	{
		if (!isset($this->_seasonName) && $this->season !== null)
			$this->_seasonName = $this->season->name;

		return $this->_seasonName;
	}

	/**
	 * Setter for _userName
	 */
	public function setUserName($id)
	{
		$this->_userName = $id;
	}

	/**
	 * Setter for _lanName
	 */
	public function setLanName($id)
	{
		$this->_lanName = $id;
	}

	/**
	 * Setter for _seasonName
	 */
	public function setSeasonName($id)
	{
		$this->_seasonName = $id;
	}

}