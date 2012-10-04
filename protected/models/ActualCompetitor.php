<?php

/**
 * This is the model class for table "tlk_actual_competitors".
 *
 * The followings are the available columns in table 'tlk_actual_competitors':
 * @property integer $id
 * @property integer $competition_id
 * @property integer $registration_id
 *
 * The followings are the available model relations:
 * @property Competition $compo
 * @property Registration $registration
 */
class ActualCompetitor extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return TlkActualCompetitor the static model class
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
		return 'tlk_actual_competitors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('competition_id, registration_id', 'required'),
			array('competition_id, registration_id', 'numerical', 'integerOnly'=>true),
			array('id, compo_id, registration_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'competition'=>array(self::BELONGS_TO, 'Competition', 'competition_id'),
			'registration'=>array(self::BELONGS_TO, 'Registration', 'registration_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'competition_id'=>'Tävling',
			'registration_id'=>'Anmälan',
		);
	}

}