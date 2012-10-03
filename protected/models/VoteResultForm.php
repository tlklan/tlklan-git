<?php

/**
 * Description of VoteResultForm
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteResultForm extends CFormModel
{

	public $competition;

	public function attributeLabels()
	{
		return array(
			'competition'=>'Tävling',
		);
	}

	public function rules()
	{
		return array(
			array('competition', 'required'),
		);
	}

}