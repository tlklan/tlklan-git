<?php

/**
 * This is the model class for table "tlk_timetable".
 *
 * The followings are the available columns in table 'tlk_timetable':
 * @property integer $id
 * @property integer $lan_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property string $name
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Lan $lan
 */
class Timetable extends CActiveRecord
{

	/**
	 * @var array list of possible event types (can be used e.g. in dropdown 
	 * lists)
	 */
	public static $types = array(
		'competition'=>'Tävling',
		'voting'=>'Röstning',
	);
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Timetable the static model class
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
		return 'tlk_timetable';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('lan_id, date', 'required'),
			array('start_time, end_time', 'date', 'format'=>array('hh:mm', 'h:mm')),
			array('lan_id', 'numerical', 'integerOnly'=>true),
			array('name, type', 'length', 'max'=>50),
			array('id, lan_id, date, start_time, end_time, name, type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'lan'=>array(self::BELONGS_TO, 'Lan', 'lan_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'lan_id'=>'Lan',
			'date'=>'Date',
			'start_time'=>'Start time',
			'end_time'=>'End time',
			'name'=>'Name',
			'type'=>'Type',
		);
	}

	/**
	 * Returns a data provider for this model
	 * @param mixed $lanId a LAN ID. Defaults to null, meaning lan_id is not 
	 * evaluated
	 * @param DateTime $date a DateTime object representing a date. Defaults to 
	 * null meaning date won't be evaluated
	 * @return \CActiveDataProvider the data provider
	 */
	public function search($lanId = null, $date = null)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('lan_id', $lanId);
		$criteria->compare('date', $date->format('Y-m-d'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns the type of this model in human-readable form
	 * @return string the type
	 */
	public function getType()
	{
		return isset(self::$types[$this->type]) ? self::$types[$this->type] : '';
	}

}