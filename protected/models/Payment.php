<?php

/**
 * This is the model class for table "tlk_payments".
 *
 * The followings are the available columns in table 'tlk_payments':
 * @property integer $id
 * @property integer $user_id
 * @property integer $lan_id
 * @property integer $season_id
 * @property string $payment_type
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Lan $lan
 * @property Season $season
 */
class Payment extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Payment the static model class
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
		return 'tlk_payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, lan_id, payment_type', 'required'),
			array('user_id, lan_id, season_id', 'numerical', 'integerOnly'=>true),
			array('payment_type', 'length', 'max'=>6),
			array('id, user_id, lan_id, season_id, payment_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'lan'=>array(self::BELONGS_TO, 'Lan', 'lan_id'),
			'season'=>array(self::BELONGS_TO, 'Season', 'season_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'user_id'=>'Användare',
			'lan_id'=>'Lan',
			'season_id'=>'Säsong',
			'payment_type'=>'Betalningstyp',
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
		$criteria->compare('user_id', $this->user_id);
		$criteria->compare('lan_id', $this->lan_id);
		$criteria->compare('season_id', $this->season_id);
		$criteria->compare('payment_type', $this->payment_type, true);

		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria));
	}

}