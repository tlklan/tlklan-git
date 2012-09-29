<?php

/**
 * Authenticates users against a local UNIX account by using SSH2.
 * Also maintains information about the user for authorization purposes.
 *
 * @author Sam
 */

class LocalUser {
	// Host parameters
	const HOSTNAME = "localhost";
	const PORT = 22;
	
	public $username;
	
	private $_conn;
	private $_uid;
	private $_gid;
	private $_groups = array();

	/**
	 * Try to establish a connection - fail if it doesn't work
	 */
	public function __construct() {
		if(!function_exists("ssh2_connect"))
			throw new Exception("SSH2-stöd saknas, kontakta serveradministratören");
		
		$this->connect(self::HOSTNAME, self::PORT);
	}

	/**
	 * Performs an SSH login with the passed credentials and sets user
	 * information. Throws Exception on failure
	 * 
	 * @param string $user local username
	 * @param string $pass local password
	 * @return boolean
	 */
	public function authenticate($user, $pass) {
		if(@ssh2_auth_password($this->_conn, $user, $pass)) {
			$this->username = $user;
			$this->setUserData();

			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Opens and stores a connection handle to the specified host
	 * 
	 * @param string $host
	 * @param int $port
	 */
	public function connect($host, $port = 22) {
		if(false === $this->_conn = @ssh2_connect($host, $port))
			throw new Exception("Kunde inte ansluta till servern");
	}

	/**
	 * Sets the $_uid, $_gid and $_groups member variables
	 */
	private function setUserData() {
		// Get the whole identity string from the server
		$stream = ssh2_exec($this->_conn, "/usr/bin/id");
		$user_data = "";

		// Parse it to a string
		while(!feof($stream)) {
			$user_data .= fgets($stream);
		}
		
		// Split the stream and set the values
		$parts = explode(" ", $user_data);
		
		$this->_uid = $this->parseId($parts[0]);
		$this->_gid = $this->parseId($parts[1]);
		$this->_groups = $this->parseGroups($parts[2]);
	}

	/***********
	 * Parser functions. Takes partial output of the "id" command as input
	 * and returns various parsed values from it
	 ***********/

	/**
	 *
	 * @param array $user_data partial output from /usr/bin/id
	 * @return array
	 */
	private function parseId($user_data) {
		$arr = array();
		preg_match("/\d+/", $user_data, $arr);

		return $arr[0];
	}

	/**
	 *
	 * @param array $user_data partial output from /usr/bin/id
	 * @return array
	 */
	private function parseGroups($user_data) {
		$arr = array();
		preg_match_all("/\d+/", $user_data, $arr);

		return $arr[0];
	}

	/**
	 * Checks if a user is a member of a specific group
	 * 
	 * @param type $gid the group ID
	 * @return type boolean true if the user is a member of group $gid, 
	 * false otherwise
	 */
	public function hasGroup($gid) {
		return in_array($gid, $this->_groups);
	}
}

?>