<?php

/**
 * This is the model class for table "tlk_registrations".
 *
 * The followings are the available columns in table 'tlk_registrations':
 * @property integer $id
 * @property integer $lan_id
 * @property integer $user_id
 * @property string $name
 * @property string $email
 * @property string $nick
 * @property string $device
 * @property string $date
 * @property integer $confirmed
 * @property integer $deleted
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Competitions[] $competitions
 * @property Lan $lan
 * @property Results[] $results
 * @property Vote[] $votes
 */
class Registration extends CActiveRecord
{
	
	/**
	 * Used for sorting grids when data is fetched throughn search()
	 * @var string
	 */
	private $_lanName;
	
	/**
	 * @var string the user's name (provided for backward compatibility)
	 */
	private $_name;
	
	/**
	 * @var string the user's email (provided for backward compatibility)
	 */
	private $_email;
	
	/**
	 * @var string the user's nick (provided for backward compatibility)
	 */
	private $_nick;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Registration the static model class
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
		return 'tlk_registrations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('lan_id, user_id, device, date', 'required'),
			array('lan_id, user_id', 'numerical', 'integerOnly'=>true),
			array('lanName, user, email, nick', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Sets some default values for new objects
	 */
	protected function afterConstruct()
	{
		$this->confirmed = false;
		$this->deleted = false;

		parent::afterConstruct();
	}

	/**
	 * Defines the default scope for this model
	 * 
	 * @return array the default scope 
	 */
	public function defaultScope()
	{
		return array(
			// Order newest first
			'order'=>$this->getTableAlias(false, false).'.id DESC',
		);
	}

	/**
	 * Returns the scopes for this model.
	 * @return array the scope definitions
	 */
	public function scopes()
	{
		return array(
			'currentLan'=>array(
				'condition'=>'lan_id = '.Lan::model()->getCurrent()->id,
			)
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'competitions'=>array(self::HAS_MANY, 'Competitor', 'registration_id'),
			'lan'=>array(self::BELONGS_TO, 'Lan', 'lan_id'),
			'results'=>array(self::HAS_MANY, 'Results', 'reg_id'),
			'votes'=>array(self::HAS_MANY, 'Vote', 'voter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'lan_id'=>'LAN',
			'user_id'=>'Användar-ID',
			'lanName'=>'LAN',
			'name'=>'Namn',
			'email'=>'E-post',
			'nick'=>'Nick',
			'device'=>'Datortyp',
			'date'=>'Anmälningsdatum',
			'confirmed'=>'Bekräftat',
			'deleted'=>'Borttagen',
		);
	}

	/**
	 * Returns true if the user is using a laptop
	 * @return boolean 
	 */
	public function hasLaptop()
	{
		return $this->device == 'laptop';
	}

	/**
	 * Returns true if the user is using a desktop
	 * @return boolean 
	 */
	public function hasDesktop()
	{
		return $this->device == 'desktop';
	}
	
	/**
	 * Checks whether this is the first time the user has been registered
	 * @return boolean
	 */
	public function isFirstTimer()
	{
		$models = Registration::model()->findAll('user_id = :user_id', array(
			':user_id'=>$this->user_id));

		return count($models) == 1;
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
	 * Getter for _email. Provided for backward compatibility + sorting/filtering
	 * @return string
	 */
	public function getEmail()
	{
		if (!isset($this->_email) && $this->user !== null)
			$this->_email = $this->user->email;

		return $this->_email;
	}

	/**
	 * Getter for _nick. Provided for backward compatibility + sorting/filtering
	 * @return string
	 */
	public function getNick()
	{
		if (!isset($this->_nick) && $this->user !== null)
			$this->_nick = $this->user->nick;

		return $this->_nick;
	}
	
	/**
	 * Getter for the lanName property. It should only be used for sorting and 
	 * filtering.
	 * @return string the name of the LAN for this registration
	 */
	public function getLanName()
	{
		if (!isset($this->_lanName) && $this->lan !== null)
			$this->_lanName = $this->lan->name;

		return $this->_lanName;
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
	 * Setter for _email. Provided for backward compatibility + sorting/filtering
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->_email = $email;
	}

	/**
	 * Setter for _nick. Provided for backward compatibility + sorting/filtering
	 * @param string $nick
	 */
	public function setNick($nick)
	{
		$this->_nick = $nick;
	}
	
	/**
	 * Setter for the lanName property. It should only be used for sorting and 
	 * filtering.
	 * @param string $name the LAN name
	 */
	public function setLanName($name)
	{
		$this->_lanName = $name;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('lan', 'user');

		$criteria->compare($this->getTableAlias().'id', $this->id);
		$criteria->compare('lan.name', $this->getLanName(), true);
		$criteria->compare('user.name', $this->name, true);
		$criteria->compare('user.email', $this->email, true);
		$criteria->compare('user.nick', $this->nick, true);
		$criteria->compare('device', $this->device, true);
		$criteria->compare('date', $this->date, true);
		$criteria->compare('confirmed', $this->confirmed);
		$criteria->compare('deleted', $this->deleted);

		return new CActiveDataProvider('Registration', array(
			'criteria'=>$criteria,
		));
	}

}