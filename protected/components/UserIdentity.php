<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		// Find the user model
		$user = User::model()->findByAttributes(array(
			'username'=>$this->username));

		// Assume the login will fail
		$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

		if ($user !== null)
		{
			// Shell login
			if ($user->hasShellAccount())
			{
				Yii::app()->localUser->connect();
				
				// Convert username to lowercase
				$username = strtolower($this->username);
				
				$valid = Yii::app()->localUser->authenticate($username, $this->password);
			}
			// Standard login
			else
				$valid = $user->checkPassword($this->password);

			// Check if login was successful
			if ($valid)
				$this->errorCode = self::ERROR_NONE;
		}

		return !$this->errorCode;
	}

}