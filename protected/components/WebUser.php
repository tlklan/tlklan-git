<?php

/**
 * Represents persistent state for the logged in user.
 *
 * @author Sam
 */
class WebUser extends CWebUser {
	/**
	 * The gid of the 'wheel' group.
	 */
	const GID_WHEEL = 99;
	
	/**
	 * Cached user object
	 * @var LocalUser 
	 */
	private $_model;
	
	/**
	 * Automatically login the user if he is already logged in on the main site
	 */
//	public function init() {
//		$localUser = Yii::app()->session->get('user');
//		
//		if($this->loadUser() === null && $localUser !== null)
//			$this->login(new UserIdentity($localUser->username, ''), 0, false);
//		
//		parent::init();
//	}

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
	 */
	public function isAdmin() {
		if($this->isGuest)
			return false;
		
		// Bypass validation when in development mode
		if(defined('YII_DEVEL_MODE') && YII_DEVEL_MODE === true)
			return true;
		
		$this->loadUser();
		
		return $this->_model->hasGroup(Yii::app()->cms->gid);
	}

	/**
	 * Wrapper for LocalUser::hasGroup()
	 * 
	 * @param int $gid the gid to check for
	 * @return boolean true if the user is a member of the specified group
	 */
	public function hasGroup($gid) {
		$this->loadUser();
		
		if($this->_model === null || !$this->_model->hasGroup($gid) )
			return false;
		
		return true;
	}
	
	/**
	 * Loads an stores the user model
	 */
	private function loadUser() {
		$this->_model = $this->getState('model');
	}
	
	/**
	 * For convenience and consistency
	 * @return string the nickname 
	 */
	public function getNick() {
		return $this->name;
	}

}