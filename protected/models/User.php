<?php

/**
 * This is the model class for table "tlk_users".
 *
 * The followings are the available columns in table 'tlk_users':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property integer $has_werket_login
 * @property string $date_added
 */
class User extends CActiveRecord
{

	/**
	 * @var string the current password (used when changing password)
	 */
	public $currentPassword;
	
	/**
	 * @var string the new password (used when changing password)
	 */
	public $newPassword;

	/**
	 * @var string the new repeated password (used when changing password)
	 */
	public $passwordRepeat;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'tlk_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, email, username', 'required'),
			array('has_werket_login', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			array('username', 'length', 'max'=>25),
			array('email', 'email'),
			
			// changePassword scenario
			array('currentPassword, newPassword, passwordRepeat', 'required', 'on'=>'changePassword'),
			array('currentPassword', 'validatePassword', 'on'=>'changePassword'),
			array('newPassword', 'compare', 'on'=>'changePassword', 'compareAttribute'=>'passwordRepeat'),
			
			// search scenario
			array('id, name, email, username, has_werket_login, date_added', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Validates the password attribute. It checks that it really is the user's 
	 * current password.
	 * @param string $attribute the attribute being validated
	 */
	public function validatePassword($attribute)
	{
		$password = $this->{$attribute};

		if (!$this->checkPassword($password))
			$this->addError($attribute, 'Felaktigt lösenord');
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Namn',
			'email'=>'E-postadress',
			'username'=>'Användarnamn',
			'password'=>'Lösenord',
			'currentPassword'=>'Nuvarande lösenord',
			'newPassword'=>'Nytt lösenord',
			'passwordRepeat'=>'Nytt lösenord (igen)',
			'has_werket_login'=>'Har Werket-konto',
			'date_added'=>'Registrerad sen',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models 
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('username', $this->username, true);
		$criteria->compare('has_werket_login', $this->has_werket_login);
		$criteria->compare('date_added', $this->date_added, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Checks whether the given password matches the model's
	 * @param string $password the password
	 * @return boolean
	 */
	public function checkPassword($password)
	{
		return Yii::app()->hasher->checkPassword($password, $this->password);
	}
	
	/**
	 * Returns true if the user has a shell account
	 * @return boolean
	 */
	public function hasShellAccount()
	{
		return $this->has_werket_login == 1;
	}

}