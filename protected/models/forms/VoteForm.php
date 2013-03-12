<?php

/**
 * Form model for the vote form
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteForm extends CFormModel
{
	/**
	 * @var int the voters ID
	 */
	public $voter;
	
	/**
	 * @var int the competition ID
	 */
	public $competition;
	
	/**
	 * @var int the submission IDs
	 */
	public $submission;

	/**
	 * Returns a list of rules for this model
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('voter, competition', 'required'),
			array('competition', 'validateCompetition'),
			array('voter', 'validateVoter'),
			array('submission', 'required'),
			array('submission', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * Returns the list of attribute labels for this model
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'competition'=>Yii::t('vote', 'Tävling'),
			'submissions'=>Yii::t('vote', 'Submission'),
		);
	}
	
	/**
	 * Validates the selected competition. It is considered invalid if its 
	 * deadline has already passed. This is a safety check only, the competition 
	 * isn't selectable from the dropdown.
	 * @param string $attribute the attribute being validated
	 */
	public function validateCompetition($attribute)
	{
		$competition = Competition::model()->findByPk($this->competition);
		if ($competition !== null && $competition->deadline <= date("Y-m-d H:i:s"))
			$this->addError($attribute, Yii::t('vote', 'Deadlinen för den här tävlingen har redan gått ut'));
	}

	/**
	 * Validates the voter. It checks that the voter hasn't already voted for 
	 * this competition
	 * @param string $attribute the attribute being validated
	 */
	public function validateVoter()
	{
		// Join the submissions table so we can check for dupes by competition ID
		$with = array(
			'submission'=>array(
				'select'=>false,
				'condition'=>'submission.competition_id = :competition',
				'params'=>array(':competition'=>$this->competition)));
		
		$votes = Vote::model()->with($with)->findByAttributes(array(
			'voter_id'=>$this->voter));

		// The voter attribute isn't shown in the form so we have to put the 
		// error elsewhere
		if (count($votes) > 0)
			$this->addError('competition', Yii::t('vote', 'Du har redan röstat i den här tävlingen'));
	}

}