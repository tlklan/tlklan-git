<?php

/**
 * This command loops through all users, generates and sets a random password 
 * for them and then e-mails it to them.
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class resetAndSendPasswordsCommand extends CConsoleCommand
{

	/**
	 * Default action
	 */
	public function actionIndex()
	{
		// Skip users that have a werket account
		$users = User::model()->findAll();

		// Password generation parameters
		$length = 8;
		$chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

		foreach ($users as $user)
		{
			// Generate a new password
			shuffle($chars);
			$password = implode(array_slice($chars, 0, $length));

			// Save it
			$user->password = Yii::app()->hasher->hashPassword($password);
			$user->save(false);
			$this->log('Updated password for '.$user->username);

			// Import Zend_Mail
			require_once('Zend/Mail.php');
			require_once('Zend/Mail/Exception.php');

			// Construct the email
			$mail = new Zend_Mail('UTF-8');
			$mail->setFrom(Yii::app()->params['mail']['from']);
			$mail->addTo($user->email);
			$mail->setSubject('Dina loginuppgifter till lan.tlk.fi');

			// The e-mail body is in a separate file for easier editing
			$body = $this->renderFile(dirname(__FILE__).'/views/resetAndSendNewPasswords.php', array(
				'user'=>$user->name,
				'username'=>$user->username,
				'password'=>$password,
				'loginUrl'=>'http://lan.tlk.fi/site/login'), true);

			$mail->setBodyText($body);

			// Send the e-mail
			try
			{
				$mail->send();

				$this->log('Successfully sent e-mail to '.$user->email);

				// Sleep for 5 seconds so we don't get caught in various filters
				$this->log('Sleeping for 5 seconds ...');
				sleep(5);
			}
			catch (Zend_Mail_Exception $e)
			{
				$this->log('ERROR: Failed to send e-mail to '.$user->email.': '.$e->getMessage());
			}
		}
	}

	/**
	 * Prints the specified message to the console along with a timestamp and 
	 * optional category.
	 * @param string $message the message
	 * @param string $category the category (optional)
	 */
	private function log($message, $category = null)
	{
		$category = $category !== null ? " [{$category}]" : ' ';
		echo date("Y-m-d H:i:s");
		echo str_pad($category, 4); // to accomodate for [] etc.
		echo $message.PHP_EOL;
	}

}