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
 * @property string $rules
 * @property int $votable
 * @property int $signupable
 * @property string $deadline
 *
 * The followings are the available model relations:
 * @property Competitor[] $competitors
 * @property int $competitorCount
 * @property Submission[] $submissions
 * @property Lan $lan
 * @property CompetitionCategory[] $categories
 */
class Competition extends CActiveRecord
{
	
	/**
	 * @var array the selected categories for this competition. Used in forms.
	 */
	public $categoryDropdownList = array();

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
			'categories'=>array(self::MANY_MANY, 'CompetitionCategory', 'tlk_competition_categories(competition_id, category_id)'),
		);
	}
	
	/**
	 * @return array validation rules
	 */
	public function rules()
	{
		return array(
			array('lan_id, short_name, full_name, votable, signupable, deadline', 'required'),
			array('lan_id', 'validateLan'),
			array('display_order, votable, signupable', 'numerical', 'integerOnly'=>true),
			array('deadline', 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'),
			array('rules', 'safe'),
			array('lan_id, display_order, short_name, full_name, votable, signupable, deadline', 'safe', 'on'=>'search'),
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
			'rules'=>Yii::t('competition', 'Regler'),
			'votable'=>Yii::t('competition', 'Kan röstas på'),
			'signupable'=>Yii::t('competition', 'Kan anmälas till'),
			'deadline'=>Yii::t('competition', 'Deadline'),
			'competitorCount'=>'Antal deltagare',
			'categoryDropdownList'=>'Kategorier',
		);
	}
	
	/**
	 * Populates the categoryList virtual attribute with the values from the 
	 * relation
	 */
	protected function afterFind()
	{
		parent::afterFind();

		// Yes, the third parameter really should be "id", otherwise the 
		// current categories won't be pre-selected
		$this->categoryDropdownList = CHtml::listData($this->categories, 'id', 'id');
	}
	
	/**
	 * Validates the lan_id attribute
	 * @param string $attribute the attribute
	 */
	public function validateLan($attribute)
	{
		if (Lan::model()->findByPk($this->{$attribute}) === null)
			$this->addError($attribute, 'Ogiltigt LAN');
	}
	
	/**
	 * Determines and sets the display_order property if it hasn't been set
	 * @return boolean whether to save or not
	 */
	protected function beforeSave()
	{
		// Determine next display order if one hasn't been set
		if (!$this->display_order)
		{
			$order = 1;

			$otherCompetitions = $this->findAllByAttributes(array(
				'lan_id'=>$this->lan_id));

			foreach ($otherCompetitions as $competition)
				if ($competition->display_order > $order)
					$order = $competition->display_order + 1;

			$this->display_order = $order;
		}

		return parent::beforeSave();
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
	
	public function getLanName()
	{
		return $this->lan->name;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search($lanId = false)
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('lan_id', $lanId ? $lanId : $this->lan_id);
		$criteria->compare('display_order', $this->display_order);
		$criteria->compare('short_name', $this->short_name, true);
		$criteria->compare('full_name', $this->full_name, true);
		$criteria->compare('rules', $this->rules, true);
		$criteria->compare('votable', $this->votable);
		$criteria->compare('signupable', $this->signupable);
		$criteria->compare('deadline', $this->deadline, true);

		$options = array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'display_order',
			),
			'pagination'=>array(
				'pageSize'=>25,
			)
		);
		
		return new CActiveDataProvider($this, $options);
	}

}