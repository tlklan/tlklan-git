<?php

/**
 * This is the model class for table "tlk_suggestions".
 *
 * The followings are the available columns in table 'tlk_suggestions':
 * @property int $id
 * @property int $user_id
 * @property string $created
 * @property string $name
 * @property string $description
 * 
 * @property int $voteCount
 * @property User $creator
 */
class Suggestion extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Suggestion the static model class
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
		return 'tlk_suggestions';
	}

	/**
	 * @return array the relations for this model
	 */
	public function relations()
	{
		return array(
			'voteCount'=>array(self::STAT, 'SuggestionVote', 'suggestion_id'),
			'creator'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, user_id, description', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'unique', 'on'=>'insert', 'message'=>Yii::t('suggest-competiton', 'Det finns redan ett identiskt förslag')),
			array('name', 'length', 'max'=>50),
			array('id, created, name, description', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'creator.nick'=>Yii::t('suggest-competiton', 'Inlagd av'),
			'created'=>Yii::t('suggest-competition', 'Inlagd'),
			'name'=>Yii::t('suggest-competiton', 'Tävling'),
			'description'=>Yii::t('suggest-competiton', 'Beskrivning'),
			'voteCount'=>Yii::t('suggest-competiton', 'Röster'),
		);
	}

	/**
	 * Retrieves a list of suggestions, including their vote count
	 * @return CActiveDataProvider a data provider
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		
		// Hack to be able to sort by vote count
		$criteria->with = 'voteCount';
		$criteria->select = '*, COUNT(suggestion_id) as voteCount';
		$criteria->join = 'LEFT JOIN tlk_suggestion_votes ON suggestion_id = id';
		$criteria->group = 'id';

		// Default sorting order
		$sort = new CSort();
		$sort->defaultOrder = 'voteCount DESC, created DESC';

		// Sort by votes and create time by default
		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria,
					'pagination'=>false,
					'sort'=>$sort));
	}
	
	/**
	 * Checks whether the specified user can edit the suggestion. Administrators 
	 * can edit all irregardless of ownership.
	 * @param int $userId the user ID to check for
	 * @return boolean
	 */
	public function isOwner($userId)
	{
		return Yii::app()->user->isAdmin() || $this->user_id == $userId;
	}

}