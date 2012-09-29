<?php

/**
 * This is the model class for table "tlk_competitors".
 *
 * The followings are the available columns in table 'tlk_competitors':
 * @property integer $id
 * @property integer $registration_id
 * @property integer $competition_id
 *
 * The followings are the available model relations:
 * @property Competitions $competition
 * @property Registrations $registration
 */
class Competitor extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Competitor the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tlk_competitors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('registration_id, competition_id', 'required'),
			array('registration_id, competition_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, registration_id, competition_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'competition' => array(self::BELONGS_TO, 'Competitions', 'competition_id'),
			'registration' => array(self::BELONGS_TO, 'Registrations', 'registration_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'registration_id' => 'Registration',
			'competition_id' => 'Competition',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('registration_id',$this->registration_id);
		$criteria->compare('competition_id',$this->competition_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}