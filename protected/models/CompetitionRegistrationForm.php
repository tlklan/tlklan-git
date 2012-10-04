<?php

/**
 * Description of CompetitionRegistrationForm
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class CompetitionRegistrationForm extends CFormModel
{

	public $registration;
	public $competition;

	public function attributeLabels()
	{
		return array(
			'registration'=>'Ditt nick',
			'competition'=>'Tävling',
		);
	}

	public function rules()
	{
		return array(
			array('registration, competition', 'required'),
			array('registration, competition', 'numerical', 'integerOnly'=>true),
		);
	}

}