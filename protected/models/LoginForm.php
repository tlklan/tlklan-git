<?php

/**
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

	public $username;
	public $password;
	public $returnUrl;
	
	private $_identity;

	public function rules() {
		return array(
			array('username, password', 'required'),
			array('returnUrl', 'safe'),
		);
	}

	public function attributeLabels() {
		return array(
			'username'=>'Användarnamn',
			'password'=>'Lösenord',
		);
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login() {
		if($this->_identity === null) {
			$this->_identity = new UserIdentity($this->username, $this->password);
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
			Yii::app()->user->login($this->_identity, 0);
			
			return true;
		}
		else {
			return false;
		}
	}
}
