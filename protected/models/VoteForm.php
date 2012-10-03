<?php

/**
 * Description of VoteForm
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteForm extends CFormModel
{

	public $nick;
	public $competition;
	public $submissions;

	public function attributeLabels()
	{
		return array(
			'nick'=>'Ditt nick',
			'competition'=>'Tävling',
			'submissions'=>'Submissions',
		);
	}

}