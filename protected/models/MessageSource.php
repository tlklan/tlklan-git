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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'category' => 'Category',
			'message' => 'Message',
            'used' => 'In Use',
		);
	}
}