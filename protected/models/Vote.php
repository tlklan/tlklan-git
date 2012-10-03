<?php

/**
 * This is the model class for table "tlk_votes".
 *
 * The followings are the available columns in table 'tlk_votes':
 * @property integer $id
 * @property integer $voter_id
 * @property integer $submission_id
 * @property integer $compo_id
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
			'compo_id'=>'Competition',
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
		$criteria->compare('voter_id', $this->voter_id);
		$criteria->compare('submission_id', $this->submission_id);
		$criteria->compare('compo_id', $this->compo_id);

		return new CActiveDataProvider(get_class($this), array(
					'criteria'=>$criteria,
				));
	}

}