<?php

/**
 * This is the model class for table "tlk_committee". It provides methods for 
 * quering committee membership status for users.
 *
 * The followings are the available columns in table 'tlk_committee':
 * @property integer $id
 * @property integer $user_id
 * @property integer $year
 * @property string $position
 *
 * The followings are the available model relations:
 * @property User $user
 */
class CommitteeMember extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CommitteeMember the static model class
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
		return 'tlk_committee';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'user_id'=>'User',
			'year'=>'Year',
			'position'=>'Position',
		);
	}

	/**
	 * Checks whether the specified user is currently on the committee
	 * @param int $userId
	 * @return boolean
	 */
	public function isCurrent($userId)
	{
		return self::model()->findByAttributes(array(
					'year'=>$this->getMaxYear(),
					'user_id'=>$userId)) !== null;
	}

	/**
	 * Checks if the specified user has previously been on the committee
	 * @param int $userId
	 * @return boolean
	 */
	public function isFormer($userId)
	{
		$attributes = array('user_id'=>$userId);
		$params = array(':maxYear'=>$this->getMaxYear());

		return self::model()->findByAttributes(
						$attributes, 'year < :maxYear', $params) !== null;
	}

	/**
	 * Checks if the specified user is a founder of LAN-klubben. A founder is 
	 * someone who has been on the committee during the first your of the clubs 
	 * existence.
	 * @param int $userId the user ID
	 * @return boolean
	 */
	public function isFounder($userId)
	{
		return self::model()->find('year = :year AND user_id = :user_id', array(
					':year'=>$this->getMinYear(),
					':user_id'=>$userId)) !== null;
	}

	/**
	 * Returns the earliest committee year
	 * @return int
	 */
	private function getMinYear()
	{
		return Yii::app()->db->createCommand('SELECT MIN(`year`) FROM 
			tlk_committee')->queryScalar();
	}

	/**
	 * Returns the latest committee year
	 * @return int
	 */
	private function getMaxYear()
	{
		return Yii::app()->db->createCommand('SELECT MAX(`year`) FROM 
			tlk_committee')->queryScalar();
	}

}