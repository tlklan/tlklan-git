<?php

/**
 * This is the model class for table "tlk_submission_votes".
 *
 * The followings are the available columns in table 'tlk_submission_votes':
 * @property int $competition_id
 * @property int $user_id
 * @property int $submission_id
 * @property string $vote_count
 * 
 * @property Competition $competition
 * @property User $user
 * @property Submission $submission
 */
class SubmissionVote extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SubmissionVote the static model class
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
		return 'tlk_submission_votes';
	}
	
	/**
	 * Yii needs a primary key to be able to handle relations. The submission_id 
	 * is unique for every row so we'll use it.
	 * @return string
	 */
	public function primaryKey()
	{
		return 'submission_id';
	}
	
	/**
	 * Defines the default scope for this model
	 * @return array the default scope 
	 */
	public function defaultScope()
	{
		return array(
			'order'=>$this->getTableAlias(false, false).'.vote_count DESC',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'competition'=>array(self::BELONGS_TO, 'Competition', 'competition_id'),
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'submission'=>array(self::BELONGS_TO, 'Submission', 'submission_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'competition_id'=>'Tävling',
			'user_id'=>'Skapare',
			'submission_id'=>'Namn',
			'vote_count'=>'Antal röster',
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
		$criteria->compare('competition_id', $this->competition_id);
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('submission_id', $this->submission_id);
		$criteria->compare('vote_count', $this->vote_count, true);

		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria));
	}
	
	public function getWinningSubmission($competitionId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'competition_id = :id';
		$criteria->order = 'vote_count DESC';
		$criteria->limit = 1;
		$criteria->params = array(':id'=>$competitionId);

		return self::model()->find($criteria);
	}

}