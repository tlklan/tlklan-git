<?php

/**
 * This is the model class for table "tlk_timetable".
 *
 * The followings are the available columns in table 'tlk_timetable':
 * @property integer $id
 * @property integer $lan_id
 * @property string $date
 * @property string $time
 * @property string $name
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Lan $lan
 */
class Timetable extends CActiveRecord
{

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
			array('lan_id, date, time, name, type', 'required'),
			array('lan_id', 'numerical', 'integerOnly'=>true),
			array('name, type', 'length', 'max'=>50),
			array('id, lan_id, date, time, name, type', 'safe', 'on'=>'search'),
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
			'time'=>'Time',
			'name'=>'Name',
			'type'=>'Type',
		);
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
		$criteria->compare('lan_id', $this->lan_id);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('time', $this->time, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('type', $this->type, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}