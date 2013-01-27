<?php

/**
 * This is the model class for table "tlk_competitions".
 *
 * The followings are the available columns in table 'tlk_competitions':
 * @property integer $id
 * @property integer $lan_id
 * @property integer $display_order
 * @property string $short_name
 * @property string $full_name
 * @property int $votable
 * @property int $signupable
 * @property string $deadline
 *
 * The followings are the available model relations:
 * @property Competitor[] $competitors
 * @property int $competitorCount
 * @property Submission[] $submissions
 * @property Lan $lan
 */
class Competition extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @return Competition the static model class
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
		return 'tlk_competitions';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'competitors'=>array(self::HAS_MANY, 'Competitor', 'competition_id'),
			'competitorCount'=>array(self::STAT, 'Competitor', 'competition_id'),
			'submissions'=>array(self::HAS_MANY, 'Submission', 'competition_id'),
			'lan'=>array(self::BELONGS_TO, 'Lan', 'lan_id'),
		);
	}
	
	/**
	 * @return array the scopes for this model
	 */
	public function scopes()
	{
		return array(
			// only returns competitions for the current LAN
			'currentLan'=>array(
				'condition'=>'lan_id = '.Lan::model()->getCurrent()->id,
			),
			// only return competitions that can't be signed up to
			'signupable'=>array(
				'condition'=>'signupable = 1',
			),
			// only return competitions that can be voted on
			'votable'=>array(
				'condition'=>'votable = 1',
			),
			// only return competitions whose deadline hasn't passed
			'undueDeadline'=>array(
				'condition'=>'deadline >= NOW()',
			)
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'lan_id'=>Yii::t('competition', 'LAN'),
			'display_order'=>Yii::t('competition', 'Ordningsnummer'),
			'short_name'=>Yii::t('competition', 'Kort namn'),
			'full_name'=>Yii::t('competition', 'Långt namn'),
			'votable'=>Yii::t('competition', 'Kan röstas på'),
			'signupable'=>Yii::t('competition', 'Kan anmälas till'),
			'deadline'=>Yii::t('competition', 'Deadline'),
		);
	}

	/**
	 * Returns a string containing the full name of the competition and it's 
	 * deadline (if available)
	 * @return string
	 */
	public function getNameAndDeadline()
	{
		if ($this->deadline !== null)
		{
			$deadline = date("Y-m-d H:i:s", strtotime($this->deadline));
			return $this->full_name.' ('.$deadline.')';
		}
		else
			return $this->full_name;
	}
	
	/**
	 * Returns a dataprovider for listing competitors for each competition
	 * @return \CActiveDataProvider
	 */
	public function getActualCompetitorDataProvider()
	{
		return new CActiveDataProvider('ActualCompetitor', array(
			'criteria'=>array(
				'condition'=>'competition_id = :competition_id',
				'params'=>array('competition_id'=>$this->id),
			),
			'pagination'=>false,
		));
	}

}