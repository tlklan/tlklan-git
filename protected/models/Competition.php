<?php

/**
 * This is the model class for table "tlk_competitions".
 *
 * The followings are the available columns in table 'tlk_competitions':
 * @property integer $id
 * @property integer $lan_id
 * @property integer $display_order
 * @property string $short_name
 * @property string $full_name
 * @property int $votable
 * @property string $deadline
 *
 * The followings are the available model relations:
 * @property Competitor[] $competitors
 * @property int $competitorCount
 * @property Submission[] $submissions
 */
class Competition extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Competition the static model class
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
		return 'tlk_competitions';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'competitors'=>array(self::HAS_MANY, 'Competitor', 'competition_id'),
			'competitorCount'=>array(self::STAT, 'Competitor', 'competition_id'),
			'submissions'=>array(self::HAS_MANY, 'Submission', 'compo_id'),
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
			'display_order'=>'Display Order',
			'short_name'=>'Short Name',
			'full_name'=>'Full Name',
			'votable'=>'Votable',
			'deadline'=>'Voting deadline',
		);
	}

	public function getNameAndDeadline()
	{
		if ($this->deadline !== null)
		{
			$deadline = date("Y-m-d H:i:s", strtotime($this->deadline));
			return $this->full_name.' ('.$deadline.')';
		}
		else
			return $this->full_name;
	}
	
	/**
	 * Returns a data provider for the submissions in this competition. It is 
	 * used on the voting results page and is order by amount of votes.
	 * @return \CArrayDataProvider
	 */
	public function getSubmissionDataProvider()
	{
		// What we need is cumbersome to accomplish with the AR system
		$rawData = Yii::app()->db->createCommand()
				->select('tlk_submissions.id, tlk_submissions.name, tlk_registrations.nick, COUNT(tlk_votes.id) AS voteCount')
				->from('tlk_submissions')
				->join('tlk_registrations', 'tlk_registrations.id = tlk_submissions.submitter_id')
				->leftJoin('tlk_votes', 'tlk_votes.submission_id = tlk_submissions.id')
				->where('tlk_submissions.compo_id = :id', array(':id'=>$this->id))
				->group('tlk_submissions.id')
				->order('voteCount DESC')
				->queryAll();

		return new CArrayDataProvider($rawData);
	}
	
	public function getActualCompetitorDataProvider()
	{
		return new CActiveDataProvider('ActualCompetitor', array(
			'criteria'=>array(
				'condition'=>'competition_id = :competition_id',
				'params'=>array('competition_id'=>$this->id),
			),
		));
	}

}