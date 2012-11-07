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
			array('password', 'required', 'on'=>'changePassword'),
			array('id, name, email, username, password, has_werket_login, date_added', 'safe', 'on'=>'search'),
		);
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
		$criteria->compare('password', $this->password, true);
		$criteria->compare('has_werket_login', $this->has_werket_login);
		$criteria->compare('date_added', $this->date_added, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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