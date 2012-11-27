<?php

/**
 * Authenticates users against a local UNIX account by using SSH2.
 * Also maintains information about the user for authorization purposes.
 *
 * @author Sam
 */
class LocalUser extends CApplicationComponent
{

	/**
	 * @var string the hostname to connect to. Defaults to "localhost"
	 */
	public $hostname = "localhost";

	/**
	 * @var int the port used for connecting. Defaults to 22.
	 */
	public $port = 22;

	/**
	 *
	 * @var Net_SSH2 the SSH connection handle
	 */
	private $_ssh;

	/**
	 * @var int the user's UID
	 */
	private $_uid;

	/**
	 * @var int the user's GID
	 */
	private $_gid;

	/**
	 * @var int[] the IDs of the UNIX groups the user is a member of
	 */
	private $_groups = array();

	/**
	 * Initializes the component
	 */
	public function init()
	{
		// Include phpseclib
		Yii::import('application.vendors.phpseclib.*');

		// This is needed because the library uses class_exists everywhere and 
		// Yii will try to auto-load, which will fail with an exception
		Yii::$enableIncludePath = false;
		require('Net/SSH2.php');

		parent::init();
	}

	/**
	 * Opens and stores a connection handle to the specified host
	 */
	public function connect()
	{
		$this->_ssh = new Net_SSH2($this->hostname, $this->port);
	}

	/**
	 * Performs an SSH login with the passed credentials and sets user
	 * information.
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function authenticate($username, $password)
	{
		if ($this->_ssh->login($username, $password))
		{
			$this->setUserData();

			return true;
		}

		return false;
	}

	/**
	 * Sets the $_uid, $_gid and $_groups member variables
	 */
	private function setUserData()
	{
		$userData = $this->_ssh->exec('/usr/bin/id');

		// Parse the returned string
		$parts = explode(" ", $userData);
		$this->_uid = $this->parseId($parts[0]);
		$this->_gid = $this->parseId($parts[1]);
		$this->_groups = $this->parseGroups($parts[2]);
	}

	/*	 * *********
	 * Parser functions. Takes partial output of the "id" command as input
	 * and returns various parsed values from it
	 * ********* */

	/**
	 *
	 * @param array $userData partial output from /usr/bin/id
	 * @return array
	 */
	private function parseId($userData)
	{
		$arr = array();
		preg_match("/\d+/", $userData, $arr);

		return $arr[0];
	}

	/**
	 *
	 * @param array $userData partial output from /usr/bin/id
	 * @return array
	 */
	private function parseGroups($userData)
	{
		$arr = array();
		preg_match_all("/\d+/", $userData, $arr);

		return $arr[0];
	}

	/**
	 * Checks if a user is a member of a specific group
	 * @param type $gid the group ID
	 * @return type boolean true if the user is a member of group $gid, 
	 * false otherwise
	 */
	public function hasGroup($gid)
	{
		return in_array($gid, $this->_groups);
	}

}