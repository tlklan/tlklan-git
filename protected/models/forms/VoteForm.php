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
	 * Initializes the model. We set some default attribute values here.
	 */
	public function init()
	{
		if (!Yii::app()->user->isGuest)
			$this->voter = Yii::app()->user->userId;

		parent::init();
	}

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
			'voter'=>'Ditt nick',
			'competition'=>'Tävling',
			'submissions'=>'Submission',
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

}