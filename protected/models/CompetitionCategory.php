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
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>50),
			array('name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>'Namn',
		);
	}

	/**
	 * Returns dropdown list options
	 * @return array
	 */
	public function getDropdownListOptions()
	{
		return CHtml::listData(self::model()->findAll(), 'id', 'name');
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}