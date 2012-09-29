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
	public static function model($className=__CLASS__)
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
			//array('id, lan_id, name, email, nick, device, date, confirmed, deleted', 'safe', 'on'=>'search'),
		);
	}

	protected function afterConstruct() {
		$this->confirmed = false;
		$this->deleted = false;
		
		parent::afterConstruct();
	}

	/**
	 * Defines the default scope for this model
	 * 
	 * @return array the default scope 
	 */
	public function defaultScope() {
		return array(
			// Order newest first
			'order'=>'id DESC',
		);
	}
	
	/**
	 * Returns the scopes for this model.
	 * @return array the scope definitions
	 */
	public function scopes() {
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
			'competitions' => array(self::HAS_MANY, 'Competitor', 'registration_id'),
			'lan' => array(self::BELONGS_TO, 'Lans', 'lan_id'),
			'results' => array(self::HAS_MANY, 'Results', 'reg_id'), // TODO: What the hell is the results table?
			'votes' => array(self::HAS_MANY, 'Vote', 'voter_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lan_id' => 'Lan',
			'name' => 'Name',
			'email' => 'Email',
			'nick' => 'Nick',
			'device' => 'Device',
			'date' => 'Date',
			'confirmed' => 'Confirmed',
			'deleted' => 'Deleted',
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
		$criteria->compare('lan_id',$this->lan_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nick',$this->nick,true);
		$criteria->compare('device',$this->device,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('confirmed',$this->confirmed);
		$criteria->compare('deleted',$this->deleted);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns a list of all registered people for the specified LAN
	 * 
	 * @param int $lanId the LAN
	 * @return array the registered people 
	 */
	public function findByLAN($lanId) {
		return $this->findAll('lan_id = :lan_id', array('lan_id'=>$lanId));
	}
	
	/**
	 * Returns true if the user is using a laptop
	 * @return boolean 
	 */
	public function hasLaptop() {
		return $this->device == 'laptop';
	}
	
	/**
	 * Returns true if the user is using a desktop
	 * @return boolean 
	 */
	public function hasDesktop() {
		return $this->device == 'desktop';
	}
}