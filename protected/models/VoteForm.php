<?php

/**
 * Description of VoteForm
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteForm extends CFormModel
{
	const MAX_VOTES = 3;

	public $voter;
	public $competition;
	public $submissions;

	public function rules()
	{
		return array(
			array('voter, competition', 'required'),
			array('voter', 'validateVoter'),
			array('submissions', 'required', 'message'=>'Du måste rösta på minst en submission'),
			array('submissions', 'validateSubmissions'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'voter'=>'Ditt nick',
			'competition'=>'Tävling',
			'submissions'=>'Submissions',
		);
	}
	
	public function validateVoter($attribute)
	{
		$votes = Vote::model()->findByAttributes(array(
			'voter_id'=>$this->voter,
			'compo_id'=>$this->competition,
		));

		if (count($votes) > 0)
			$this->addError($attribute, 'Du har redan röstat i den här tävlingen');
	}
	
	public function validateSubmissions($attribute)
	{
		if (count($this->submissions) > self::MAX_VOTES)
			$this->addError($attribute, 'Du kan rösta på högst tre inlägg');
	}

}