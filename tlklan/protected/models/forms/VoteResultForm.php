<?php

/**
 * Form model for the vote result form (basicallyt just a dropdown)
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class VoteResultForm extends CFormModel
{

	/**
	 * @var int competition ID
	 */
	public $competition;

	/**
	 * Returns the list of attribute labels for this model
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'competition'=>Yii::t('vote', 'Tävling'),
		);
	}

	/**
	 * Returns a list of rules for this model
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('competition', 'required'),
		);
	}

}