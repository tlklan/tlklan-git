<?php

/**
 * This is the model class for table "tlk_lans".
 *
 * The followings are the available columns in table 'tlk_lans':
 * @property int $id
 * @property int $season_id
 * @property string $name
 * @property int $reg_limit
 * @property string $start_date
 * @property string $end_date
 * @property string $location
 * @property int $enabled
 *
 * The followings are the available model relations:
 * @property Season $season
 * @property Competition[] $competitions
 * @property Registration[] $registrations
 */
class Lan extends CActiveRecord
{

	// Defined locations
	const LOCATION_CORNER		= 'corner';
	const LOCATION_WERKET		= 'werket';
	const LOCATION_HARTWALL		= 'hartwall';
	
	/**
	 * @var int the season ID. This property is used for sorting/filtering grid 
	 * views
	 */
	private $_seasonId;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Lan the static model class
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
		return 'tlk_lans';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, reg_limit, start_date, end_date, location', 'required'),
			array('reg_limit, enabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			array('start_date, end_date', 'date', 'format'=>'yyyy-MM-dd'),
			array('location', 'validateLocation'),
			array('id, name, seasonId, reg_limit, start_date, end_date, location, enabled', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'season'=>array(self::BELONGS_TO, 'Season', 'season_id'),
			'competitions'=>array(self::HAS_MANY, 'Competition', 'lan_id', 'order'=>'display_order'),
			'registrations'=>array(self::HAS_MANY, 'Registration', 'lan_id'),
		);
	}

	/**
	 * Defines the default scope for this model
	 * 
	 * @return array the default scope 
	 */
	public function defaultScope()
	{
		return array(
			'order'=>$this->getTableAlias(false, false).'.id DESC',
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Namn',
			'seasonId'=>'Säsong',
			'reg_limit'=>'Max antal deltagare',
			'start_date'=>'Startdatum',
			'end_date'=>'Slutdatum',
			'location'=>'Plats',
			'enabled'=>in_array($this->scenario, array('insert', 'update')) ? 'Sätt som aktivt' : 'Aktivt',
		);
	}
	
	/**
	 * Checks that the selected location is valid
	 * @param string $attribute the attribute being validated
	 */
	public function validateLocation($attribute)
	{
		if (!array_key_exists($this->{$attribute}, $this->getLocationList()))
			$this->addError($attribute, 'Ogiltig plats');
	}
	
	/**
	 * This method is run after the model is saved. It disables all other LANs 
	 * if the "enabled" attribute is set to true.
	 */
	protected function afterSave()
	{
		parent::afterSave();

		if ($this->enabled)
		{
			Yii::app()->db->createCommand()->update($this->tableName(), 
					array('enabled'=>0), 'id != :id', array(':id'=>$this->id));
		}
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = 'season';
		
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('season.id', $this->getSeasonId(), true);
		$criteria->compare('reg_limit', $this->reg_limit);
		$criteria->compare('start_date', $this->start_date, true);
		$criteria->compare('end_date', $this->end_date, true);
		$criteria->compare('location', $this->location, true);
		$criteria->compare('enabled', $this->enabled, true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20,
			)
		));
	}

	/**
	 * Returns a list of this LANs competitions, sorted by their amount of 
	 * registered competitors
	 * @return array the statistics (compo=>count)
	 */
	public function getCompetitionStatistics()
	{
		$stats = array();

		foreach ($this->competitions as $competition)
			$stats[$competition->short_name] = $competition->competitorCount;

		arsort($stats);

		return $stats;
	}

	/**
	 * Returns the model for the current LAN
	 * @return Lan the model
	 */
	public function getCurrent()
	{
		return self::model()->find('enabled = 1');
	}
	
	/**
	 * Returns a list of valid LAN locations (can be used for grid view filters 
	 * or drop down lists)
	 * @return array
	 */
	public function getLocationList()
	{
		return array(
			Lan::LOCATION_CORNER=>'Cornern',
			Lan::LOCATION_WERKET=>'Werket',
			Lan::LOCATION_HARTWALL=>'Hartwall Arena'
		);
	}

	/**
	 * Returns the friendly name of the LAN location
	 * @return string
	 */
	public function getFriendlyLocation()
	{
		switch ($this->location)
		{
			case self::LOCATION_CORNER:
				return 'Cornern';
				break;
			case self::LOCATION_WERKET:
				return 'Werket';
				break;
			case self::LOCATION_HARTWALL:
				return 'Hartwall Arena';
				break;
			default:
				return '';
		}
	}
	
	/**
	 * Getter for _seasonId. This method is used when sorting/filtering grid 
	 * views
	 * @return int the season ID
	 */
	public function getSeasonId()
	{
		if (!isset($this->_seasonId) && $this->season !== null)
			$this->_seasonId = $this->season->id;

		return $this->_seasonId;
	}

	/**
	 * Setter for _seasonId. This method is used when sorting/filtering grid 
	 * views
	 * @param int $id
	 */
	public function setSeasonId($id)
	{
		$this->_seasonId = $id;
	}
	
	/**
	 * Checks whether this LAN is full booked
	 * @return boolean whether it's full
	 */
	public function isFull()
	{
		return count($this->registrations) >= $this->reg_limit;
	}
	
}