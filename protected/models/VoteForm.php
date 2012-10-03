<?php

/**
 * Form model for the vote form
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteForm extends CFormModel
{
	/**
	 * Maximum amount of votes a person can cast
	 */
	const MAX_VOTES = 3;

	/**
	 * @var int the voters ID
	 */
	public $voter;
	
	/**
	 * @var int the competition ID
	 */
	public $competition;
	
	/**
	 * @var array the submission IDs
	 */
	public $submissions;

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
			array('submissions', 'required', 'message'=>'Du måste rösta på minst en submission'),
			array('submissions', 'validateSubmissions'),
		);
	}

	/**
	 * Returns the list of attribute labels for this model
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'voter'=>'Ditt nick',
			'competition'=>'Tävling',
			'submissions'=>'Submissions',
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
			$this->addError($attribute, 'Deadlinen för den här tävlingen har redan gått ut');
	}

	/**
	 * Validates the voter. It checks that the voter hasn't already voted for 
	 * this competition
	 * @param string $attribute the attribute being validated
	 */
	public function validateVoter($attribute)
	{
		$votes = Vote::model()->findByAttributes(array(
			'voter_id'=>$this->voter,
			'compo_id'=>$this->competition,
		));

		if (count($votes) > 0)
			$this->addError($attribute, 'Du har redan röstat i den här tävlingen');
	}
	
	/**
	 * Validates the choice of submissions. It checks that the voter isn't 
	 * trying to cast more than @see VoteForm::MAX_VOTES votes.
	 * @param string $attribute the attribute being validated
	 */
	public function validateSubmissions($attribute)
	{
		if (count($this->submissions) > self::MAX_VOTES)
			$this->addError($attribute, 'Du kan rösta på högst tre inlägg');
	}

}