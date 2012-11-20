<?php

/**
 * This is the model class for table "tlk_lans".
 *
 * The followings are the available columns in table 'tlk_lans':
 * @property integer $id
 * @property string $name
 * @property integer $reg_limit
 * @property string $start_date
 * @property string $end_date
 * @property string $location
 * @property integer $enabled
 *
 * The followings are the available model relations:
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
			array('name, reg_limit, start_date, end_date', 'required'),
			array('reg_limit, enabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>20),
			array('start_date, end_date', 'date', 'format'=>'yyyy-MM-dd'),
			// TODO: Add location rule
			array('id, name, reg_limit, start_date, end_date, enabled', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'competitions'=>array(self::HAS_MANY, 'Competition', 'lan_id'),
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
			'reg_limit'=>'Max antal deltagare',
			'start_date'=>'Startdatum',
			'end_date'=>'Slutdatum',
			'location'=>'Plats',
			'enabled'=>in_array($this->scenario, array('insert', 'update')) ? 'Sätt som aktivt' : 'Aktivt',
		);
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
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
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
	 * Checks whether this LAN is full booked
	 * @return boolean whether it's full
	 */
	public function isFull()
	{
		return count($this->registrations) >= $this->reg_limit;
	}
	
	

}