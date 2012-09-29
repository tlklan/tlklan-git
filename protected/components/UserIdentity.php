<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

	public $localUser;
	
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
		// Use the basic authentication mode when developing
		if(defined('YII_DEVEL_MODE') && YII_DEVEL_MODE === true)
			return $this->develAuthenticate();
		
		// Try to authenticate the user
		$this->localUser = new LocalUser();
		
		if($this->localUser->authenticate($this->username, $this->password)) {
			$this->errorCode=self::ERROR_NONE;
		}
		else {
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
		}
		
		return !$this->errorCode;
	}
	
	/**
	 * This method uses a different authentication method so that developers 
	 * don't have to install an SSH server on their machines (difficult when on 
	 * Windows). It authenticates the user against the devUsers parameter 
	 * defined in the application configuration.
	 * @return boolean true if no error code was encountered
	 */
	private function develAuthenticate()
	{
		// Get the list of valid users
		$users = Yii::app()->params['devUsers'];
		
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		
		return !$this->errorCode;
	}
	
}