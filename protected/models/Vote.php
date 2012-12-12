<?php

/**
 * This is the model class for table "tlk_votes".
 *
 * The followings are the available columns in table 'tlk_votes':
 * @property integer $id
 * @property integer $voter_id
 * @property integer $submission_id
 * @property integer $competition_id
 *
 * The followings are the available model relations:
 * @property Submission $submission
 * @property Registration $voter
 */
class Vote extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Vote the static model class
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
		return 'tlk_votes';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'voter_id'=>'Voter',
			'submission_id'=>'Submission',
			'competition_id'=>'Competition',
		);
	}

}