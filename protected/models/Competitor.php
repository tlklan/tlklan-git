<?php

/**
 * This is the model class for table "tlk_competitors".
 *
 * The followings are the available columns in table 'tlk_competitors':
 * @property integer $id
 * @property integer $registration_id
 * @property integer $competition_id
 */
class Competitor extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Competitor the static model class
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
		return 'tlk_competitors';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'registration_id'=>'Registration',
			'competition_id'=>'Competition',
		);
	}

}