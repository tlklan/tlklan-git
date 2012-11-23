<?php

/**
 * This is the model class for table "tlk_seasons".
 *
 * The followings are the available columns in table 'tlk_seasons':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Payment[] $payments
 */
class Season extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Season the static model class
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
		return 'tlk_seasons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>20),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'payments'=>array(self::HAS_MANY, 'Payment', 'season_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Namn',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);

		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria));
	}
	
	/**
	 * Returns dropdown list options
	 * @return array
	 */
	public function getDropdownListOptions()
	{
		return CHtml::listData(self::model()->findAll(), 'id', 'name');
	}

}