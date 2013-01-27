<?php

/**
 * The followings are the available columns in table 'tlk_source_messages':
 * @property integer $id
 * @property string $category
 * @property string $message
 * @property int $used
 */
class MessageSource extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
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
		return 'tlk_source_messages';
	}

	/**
	 * Returns the relations for this model
	 * @return array the relations 
	 */
	public function relations()
	{
		return array(
			'translations'=>array(self::HAS_MANY, 'Message', 'id'),
		);
	}

	/**
	 * Defines the default scope for this model
	 * @return array the default scope
	 */
	public function defaultScope()
	{
		// Default sorting order (it should always be sorted this way)
		return array(
			'order'=>'category, message',
		);
	}

	/**
	 * The scopes for this model
	 * @return array the scopes
	 */
	public function scopes()
	{
		// Returns only the rows that are marked as in use
		return array(
			'inUse'=>array(
				'condition'=>'used = 1',
			)
		);
	}
	
	/**
	 * Scope for including only the messages belonging to the specified 
	 * category. If the category parameter is left to false (default) the 
	 * filter is not applied (thus it can be used even when not wanting to 
	 * actually filter)
	 * @param string $category the category
	 * @return MessageSource this
	 */
	public function filterCategory($category = false)
	{
		if ($category !== false)
		{
			$this->getDbCriteria()->mergeWith(array(
				'condition'=>"category = '$category'",
			));
		}

		return $this;
	}
	
}