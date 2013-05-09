<?php

/**
 * This is the model class for table "tlk_suggestion_votes".
 *
 * The followings are the available columns in table 'tlk_suggestion_votes':
 * @property integer $suggestion_id
 * @property integer $user_id
 */
class SuggestionVote extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SuggestionVote the static model class
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
		return 'tlk_suggestion_votes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('suggestion_id, user_id', 'required'),
			// validate uniqueness based on both user_id and suggestion_id
			// the message isn't actually used, the rule is only there to throw 
			// errors on dupes
			array('user_id', 'unique', 'criteria'=>array(
					'condition'=>'suggestion_id = :suggestionId',
					'params'=>array(':suggestionId'=>$this->suggestion_id))),
			array('suggestion_id, user_id', 'numerical', 'integerOnly'=>true),
			array('suggestion_id, user_id', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'suggestion_id'=>'Suggestion',
			'user_id'=>'User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('suggestion_id', $this->suggestion_id);
		$criteria->compare('user_id', $this->user_id);

		return new CActiveDataProvider($this, array(
					'criteria'=>$criteria));
	}

}