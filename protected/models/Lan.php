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
 * @property integer $enabled
 *
 * The followings are the available model relations:
 * @property Competition[] $competitions
 * @property Registration[] $registrations
 */
class Lan extends CActiveRecord
{

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
			// Order newest first
			'order'=>'id DESC',
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Name',
			'reg_limit'=>'Reg Limit',
			'start_date'=>'Start Date',
			'end_date'=>'End Date',
			'enabled'=>'Enabled',
		);
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