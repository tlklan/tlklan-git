<?php

/**
 * This is the model class for table "tlk_registrations".
 *
 * The followings are the available columns in table 'tlk_registrations':
 * @property integer $id
 * @property integer $lan_id
 * @property string $name
 * @property string $email
 * @property string $nick
 * @property string $device
 * @property string $date
 * @property integer $confirmed
 * @property integer $deleted
 *
 * The followings are the available model relations:
 * @property Competitions[] $competitions
 * @property Lans $lan
 * @property Results[] $results
 * @property Vote[] $votes
 */
class Registration extends CActiveRecord
{

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
			array('lan_id, name, email, nick, device, date', 'required'),
			array('lan_id', 'numerical', 'integerOnly'=>true),
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
			'competitions'=>array(self::HAS_MANY, 'Competitor', 'registration_id'),
			'lan'=>array(self::BELONGS_TO, 'Lans', 'lan_id'),
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
			'lan_id'=>'Lan',
			'name'=>'Name',
			'email'=>'Email',
			'nick'=>'Nick',
			'device'=>'Device',
			'date'=>'Date',
			'confirmed'=>'Confirmed',
			'deleted'=>'Deleted',
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
	 * Finds the registration model for the specified nick (only for the current 
	 * LAN)
	 * @param string $nick the nick
	 * @return Registration the model, or null if not found
	 */
	public function findByNick($nick)
	{
		$currentLan = Lan::model()->getCurrent();

		return Registration::model()->findByAttributes(array(
			'lan_id'=>$currentLan->id,
			'nick'=>$nick,
		));
	}
	
	/**
	 * Checks whether this is the first time this nick has been registered
	 * @return boolean
	 */
	public function isFirstTimer()
	{
		$models = Registration::model()->findAll('nick = :nick OR name = :name', array(
			':nick'=>$this->nick,
			':name'=>$this->name,
		));

		return count($models) == 1;
	}

}