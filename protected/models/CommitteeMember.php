<?php

/**
 * This is the model class for table "tlk_committee".
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
			'user'=>array(self::BELONGS_TO, 'TlkUsers', 'user_id'),
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
	 * Returns the current committee's members
	 * @return CommitteeMember[]
	 */
	public function getCurrentCommitteeMembers()
	{
		$maxYear = Yii::app()->db->createCommand('SELECT MAX(`year`) FROM tlk_committee')->queryScalar();

		return self::model()->findAll('year = :year', array(':year'=>$maxYear));
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
		$minYear = Yii::app()->db->createCommand('SELECT MIN(`year`) FROM tlk_committee')->queryScalar();

		$model = self::model()->find('year = :year AND user_id = :user_id', array(
			':year'=>$minYear,
			':user_id'=>$userId));

		return $model !== null;
	}

}