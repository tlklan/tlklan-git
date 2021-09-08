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

	const POSITION_CHAIRMAN      = 'ordforande';
	const POSITION_VICE_CHAIRMAN = 'vice';
	const POSITION_CFO           = 'ekonom';
	const POSITION_SECRETARY     = 'sekreterare';
	const POSITION_EVENT_LEADER  = 'verkledare';

	/**
	 * @var array human readable names of the various positons
	 */
	public static $availablePositions = array(
		self::POSITION_CHAIRMAN      => 'Ordförande',
		self::POSITION_VICE_CHAIRMAN => 'Viceordförande',
		self::POSITION_CFO           => 'Ekonom',
		self::POSITION_SECRETARY     => 'Sekreterare',
		self::POSITION_EVENT_LEADER  => 'Verksamhetsledare',
	);

	/**
	 * @var string used for sorting grids when data is fetched through search()
	 */
	private $_name;

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
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>'Namn',
			'user_id'=>'Namn',
			'year'=>'År',
			'position'=>'Position',
		);
	}


	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array(
			array('name, user_id, year, position', 'required'),
			array('year', 'numerical', 'integerOnly'=>true, 'min'=>2010),
			array('name, user_id, year, position', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Runs before validation. It fetches the actual user_id of the user. If
	 * not found it sets it to 0 so we don't get a "user can't be empty"
	 * message instead of the real error.
	 * @return boolean whether to continue validation
	 */
	protected function beforeValidate()
	{
		// Find the actual user_id
		$user = User::model()->findByAttributes(array('name'=>$this->_name));
		$this->user_id = $user !== null ? $user->id : 0;

		return parent::beforeValidate();
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('user');

		$criteria->compare('id', $this->id);
		$criteria->compare('user.name', $this->name, true);
		$criteria->compare('year', $this->year, true);
		$criteria->compare('position', $this->position, true);

		return new CActiveDataProvider($this, array(
			'criteria'   => $criteria,
			'pagination' => array(
				'pageSize' => 20,
			),
		));
	}

	/**
	 * Getter for _name. Provided for backward compatibility + sorting/filtering
	 * @return string
	 */
	public function getName()
	{
		if (!isset($this->_name) && $this->user !== null)
			$this->_name = $this->user->name;

		return $this->_name;
	}

	/**
	 * Setter for _name. Provided for backward compatibility + sorting/filtering
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->_name = $name;
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
	 * Checks whether the user was on the board during the specified year
	 * @param int $userId the user
	 * @param int $year the year
	 * @return boolean
	 */
	public function wasDuring($userId, $year)
	{
		return self::model()->findByAttributes(array(
					'year'=>$year,
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
		return self::model()->findByAttributes(array(
					'year'=>$this->getMinYear(), 'user_id'=>$userId)) !== null;
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
