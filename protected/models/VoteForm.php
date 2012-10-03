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
	
	public function validateSubmissions($attribute, $value)
	{
		if (count($this->submissions) > self::MAX_VOTES)
			$this->addError($attribute, 'Du kan rösta på högst tre inlägg');
	}

}