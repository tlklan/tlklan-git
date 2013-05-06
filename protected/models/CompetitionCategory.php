<?php

/**
 * This is the model class for table "tlk_competition_category".
 *
 * The followings are the available columns in table 'tlk_competition_category':
 * @property string $name
 */
class CompetitionCategory extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CompetitionCategory the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tlk_competition_category';
	}

}