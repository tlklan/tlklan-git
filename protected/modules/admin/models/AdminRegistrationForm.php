<?php

/**
 * Form model for updating registration models in the backend.
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class AdminRegistrationForm extends RegistrationForm
{

	/**
	 * @var int set to 1 if the user never showed up on the LAN
	 */
	public $never_showed;
	
	/**
	 * Returns the validation rules for this model. We override the parent 
	 * implementation because we don't need to be as strict here.
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('name, email, nick, device', 'required'),
			array('never_showed', 'numerical', 'integerOnly'=>true),
			array('email', 'email', 'message'=>'Ogiltig e-postadress'),
			array('competitions', 'validateCompetitions'),
		);
	}

	/**
	 * Returns the attribute labels for this model. We change some labels to 
	 * better fit the backend context.
	 * @return array
	 */
	public function attributeLabels()
	{
		return CMap::mergeArray(parent::attributeLabels(), array(
			'device'=>'Apparat',
			'competitions'=>'Tävlingar',
			'never_showed'=>'Dök aldrig upp',
		));
	}
	
	/**
	 * Overrides parent implementation so that the never_showed attribute can 
	 * be populated
	 * @param Registration $model
	 */
	public function populate($model)
	{
		parent::populate($model);

		$this->never_showed = $model->never_showed;
	}

}