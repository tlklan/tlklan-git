<?php

/**
 * Represents persistent state for the logged in user.
 *
 * @author Sam
 * @property string $email 
 * @property string $nick
 * @property int userId
 */
class WebUser extends CWebUser 
{
	
	/**
	 * @var int the gid of the UNIX group which users should be administrators 
	 * on the site
	 */
	public $gid;
	
	/**
	 * Cached user object
	 * @var LocalUser 
	 */
	private $_localUser;
	
	/**
	 * @var User the user model
	 */
	private $_user;
	
	/**
	 * We need to store the localUser object from the user identity so we can
	 * do checks against it without having to authenticate the user again. We
	 * also store the LocalUser object in a separate session variable for 
	 * compatibility with the main site
	 * 
	 * @param UserIdentity $identity
	 * @param int $duration duration of the login
	 * @param boolean $includeMainLogin whether to also login on the main site
	 */
	public function login($identity, $duration = 0, $includeMainLogin = true) {
		$this->setState('model', $identity->localUser);
		
		if($includeMainLogin)
			Yii::app()->session->add('user', $identity->localUser);
		
		parent::login($identity, $duration);
	}
	
	/**
	 * Overriden from parent to be able to also log out at the main site
	 * 
	 * @see CWebUser::logout()
	 * @param boolean $destroySession 
	 */
	public function logout($destroySession = true) {
		Yii::app()->session->remove('user');
		
		parent::logout($destroySession);
	}

	
	/**
	 * Returns true if the user is an administrator.
	 * @return boolean
	 */
	public function isAdmin()
	{
		if ($this->isGuest)
			return false;

		$this->loadUser();

		if ($this->_user->id == 1)
			return true;
		else
			return $this->hasGroup($this->gid);
	}

	/**
	 * Wrapper for LocalUser::hasGroup()
	 * 
	 * @param int $gid the gid to check for
	 * @return boolean true if the user is a member of the specified group
	 */
	public function hasGroup($gid) {
		$this->loadUser();
		
		if($this->_localUser === null || !$this->_localUser->hasGroup($gid) )
			return false;
		
		return true;
	}
	
	/**
	 * Loads an stores the user model(s)
	 */
	private function loadUser()
	{
		// Get the LocalUser object
		$this->_localUser = $this->getState('model');

		// Get the user model
		$this->_user = User::model()->find('username = :username', array(
			':username'=>Yii::app()->user->id,
		));
	}
	
	/**
	 * Returns the user's full name
	 * @return string 
	 */
	public function getName()
	{
		$this->loadUser();

		return $this->_user->name;
	}

	/**
	 * Returns the user's email address
	 * @return string 
	 */
	public function getEmail()
	{
		$this->loadUser();

		return $this->_user->email;
	}

	/**
	 * Returns the user's nickname
	 * @return string 
	 */
	public function getNick()
	{
		$this->loadUser();
		
		return $this->_user->nick;
	}
	
	/**
	 * Returns the user's username
	 * @return string 
	 */
	public function getUsername()
	{
		$this->loadUser();

		return $this->_user->username;
	}
	
	/**
	 * Returns the user's ID
	 * @return string 
	 */
	public function getUserId()
	{
		$this->loadUser();

		return $this->_user->id;
	}

}