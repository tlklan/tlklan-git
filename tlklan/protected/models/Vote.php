<?php

/**
 * This is the model class for table "tlk_votes".
 *
 * The followings are the available columns in table 'tlk_votes':
 * @property integer $id
 * @property integer $voter_id
 * @property integer $submission_id
 *
 * The followings are the available model relations:
 * @property Submission $submission
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
	 * @return array the relations for this model
	 */
	public function relations()
	{
		return array(
			'submission'=>array(self::BELONGS_TO, 'Submission', 'submission_id'),
		);
	}
	
}