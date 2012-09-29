<?php

/**
 * This is the model class for table "tlk_votes".
 *
 * The followings are the available columns in table 'tlk_votes':
 * @property integer $id
 * @property integer $voter_id
 * @property integer $submission_id
 * @property integer $compo_id
 *
 * The followings are the available model relations:
 * @property Submissions $submission
 * @property Registrations $voter
 */
class Vote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Vote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tlk_votes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('voter_id, submission_id, compo_id', 'required'),
			array('voter_id, submission_id, compo_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, voter_id, submission_id, compo_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'submission' => array(self::BELONGS_TO, 'Submissions', 'submission_id'),
			'voter' => array(self::BELONGS_TO, 'Registrations', 'voter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'voter_id' => 'Voter',
			'submission_id' => 'Submission',
			'compo_id' => 'Compo',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('voter_id',$this->voter_id);
		$criteria->compare('submission_id',$this->submission_id);
		$criteria->compare('compo_id',$this->compo_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}