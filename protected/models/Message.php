<?php

/**
 * The followings are the available columns in table 'tlk_translated_messages':
 * @property integer $id
 * @property string $language
 * @property string $translation
 */
class Message extends CActiveRecord
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
		return 'tlk_translated_messages';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'Id',
			'language'=>'Language',
			'translation'=>'Translation',
		);
	}

	/**
	 * Filters out all translations except the one for the specified language.
	 * @param string $language the language
	 * @return \Message this
	 */
	public function filterLanguage($language)
	{
		$this->getDbCriteria()->mergeWith(array(
			'condition'=>"language = '$language'",
		));

		return $this;
	}

}