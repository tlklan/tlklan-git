<?php

/**
 * Admin-specific implementation of the Registration model. We override it in 
 * order to change a few attribute labels.
 *
 * @author sam
 */
class AdminRegistration extends Registration
{

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Returns the attribute labels for this model. We change some labels to 
	 * better fit the backend context.
	 * @return array
	 */
	public function attributeLabels()
	{
		return CMap::mergeArray(parent::attributeLabels(), array(
					'device' => 'Apparat',
					'competitionList' => 'Tävlingar',
		));
	}

}