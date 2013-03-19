<?php

/**
 * This is the model class for table "tlk_users".
 *
 * The followings are the available columns in table 'tlk_users':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $nick
 * @property string $password
 * @property int $image_id
 * @property integer $has_werket_login
 * @property string $date_added
 * @property boolean $removeProfileImage
 * 
 * @property int $lanCount
 * @property Submission[] $submissions
 * @property int $submissionCount
 * @property Lan[] $lans
 * @property Registration[] $registrations
 * @property Competition[] $competitions
 * @property Image $image
 * @property Payment[] $payments
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
	 * @var CUploadedFile eventual uploaded profile pictures
	 */
	public $profileImage;
	
	/**
	 * @var boolean whether to remove the currently stored profile image
	 */
	public $removeProfileImage = false;
	
	/**
	 * @var Competititon[] runtime cache for getWonCompetitions()
	 * @see User::getWonCompetitions()
	 */
	private $_wonCompetitions;
	
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
			array('name, email, nick', 'required'),
			array('has_werket_login, image_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			array('username, nick', 'length', 'max'=>25),
			array('email', 'email'),
			array('profileImage', 'file', 'allowEmpty'=>true, 'types'=>array('gif', 'jpeg', 'jpg', 'png')),
			
			// update scenario
			array('removeProfileImage', 'required', 'on'=>'update'),
			
			// register new user (insert) scenario
			array('username, newPassword, passwordRepeat, has_werket_login', 'required', 'on'=>'insert'),
			array('email', 'validateDuplicates', 'on'=>'insert'),
			
			// don't require passwords if the user has a werket account
			array('newPassword, passwordRepeat', 'safe', 'on'=>'insert-has-werket'),
			
			// changePassword scenario
			array('currentPassword, newPassword, passwordRepeat', 'required', 'on'=>'changePassword'),
			array('currentPassword', 'validatePassword', 'on'=>'changePassword'),
			
			// insert/changePassword scenario
			array('newPassword', 'compare', 'on'=>'changePassword, insert', 'compareAttribute'=>'passwordRepeat'),
			
			// update-admin scenario
			array('has_werket_login', 'required', 'on'=>'update-admin'),
			array('passwordRepeat', 'safe', 'on'=>'update-admin'),
			array('newPassword', 'compare', 'on'=>'update-admin', 'allowEmpty'=>true, 'compareAttribute'=>'passwordRepeat'),
			
			// search scenario
			array('id, name, email, username, has_werket_login, date_added', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Checks that both e-mail and username is unique
	 * @param string $attribute the attribute being validated
	 */
	public function validateDuplicates($attribute)
	{
		$dupes = User::model()->findAll('email = :email OR username = :username', array(
			':email'=>$this->{$attribute},
			':username'=>$this->username));

		if (count($dupes) > 0)
			$this->addError('email', Yii::t('user', 'Din e-postadress eller ditt nickname finns redan'));
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
			$this->addError($attribute, Yii::t('user', 'Felaktigt lösenord'));
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'lanCount'=>array(self::STAT, 'Lan', 'tlk_registrations(lan_id, user_id)'),
			'submissions'=>array(self::HAS_MANY, 'Submission', 'user_id', 'order'=>'submissions.id DESC'),
			'submissionCount'=>array(self::STAT, 'Submission', 'user_id'),
			'registrations'=>array(self::HAS_MANY, 'Registration', 'user_id'),
			'registrationCount'=>array(self::STAT, 'Registration', 'user_id'),
			'lans'=>array(self::HAS_MANY, 'Lan', array('lan_id'=>'id'), 'through'=>'registrations'),
			// the following relation is only used as an intermediate to get the 
			// competitions relation
			'competitors'=>array(self::HAS_MANY, 'Competitor', array('id'=>'registration_id'), 'through'=>'registrations'),
			'competitions'=>array(self::HAS_MANY, 'Competition', array('competition_id'=>'id'), 'through'=>'competitors'),
			'image'=>array(self::HAS_ONE, 'Image', array('id'=>'image_id')),
			'payments'=>array(self::HAS_MANY, 'Payment', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>Yii::t('user', 'Namn'),
			'email'=>Yii::t('user', 'E-postadress'),
			'username'=>Yii::t('user', 'Användarnamn'),
			'nick'=>Yii::t('user', 'Nick'),
			'profileImage'=>Yii::t('user', 'Profilbild'),
			'password'=>Yii::t('user', 'Lösenord'),
			'currentPassword'=>Yii::t('user', 'Nuvarande lösenord'),
			'newPassword'=>Yii::t('user', 'Nytt lösenord'),
			'passwordRepeat'=>Yii::t('user', 'Nytt lösenord (igen)'),
			'has_werket_login'=>$this->scenario == 'update-admin' ? Yii::t('user', 'Har konto på werket.tlk.fi') : Yii::t('user', 'Jag har ett konto på werket.tlk.fi'),
			'date_added'=>Yii::t('user', 'Registrerad sen'),
			'removeProfileImage'=>Yii::t('user', 'Ta bort min nuvarande profilbild'),
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
			'pagination'=>array(
				'pageSize'=>20,
			)
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
	 * Returns the competitions that the user has won. Only competitions where 
	 * a vote has taken place will be included. The result is stored in 
	 * _wonCompetitions so it can be reused during the same request (since this 
	 * method is fairly expensive).
	 * @param boolean $withDeadlines don't include competitions which deadlines 
	 * haven't passed
	 * @return Competititon[]
	 */
	public function getWonCompetitions($withDeadlines = true)
	{
		if ($this->_wonCompetitions === null)
		{
			$this->_wonCompetitions = array();
			$staticModel = SubmissionVote::model(); // reuse inside loop

			foreach ($this->competitions as $competition)
			{
				$winner = $staticModel->getWinningSubmission($competition->id);

				if ($winner !== null && $winner->user_id == $this->id)
				{
					if ($withDeadlines && strtotime($competition->deadline) > time())
						continue;

					$this->_wonCompetitions[] = $competition;
				}
			}
		}

		return $this->_wonCompetitions;
	}
	
	/**
	 * Returns true if the user has a shell account
	 * @return boolean
	 */
	public function hasShellAccount()
	{
		return $this->has_werket_login == 1;
	}
	
	/**
	 * Returns an array of badges that the user has earned
	 * @return Badge[] the user's badges
	 */
	public function getBadges()
	{
		$badges = array();

		// User is a current committee member
		if (CommitteeMember::model()->isCurrent($this->id))
			$badges[] = new Badge(Badge::BADGE_IS_CURRENT_COM_MEMBER);

		// User is a former committee member
		if (CommitteeMember::model()->isFormer($this->id))
			$badges[] = new Badge(Badge::BADGE_FORMER_COM_MEMBER);
		
		// Is founding father?
		if (CommitteeMember::model()->isFounder($this->id))
			$badges[] = new Badge(Badge::BADGE_IS_FOUNDING_FATHER);
		
		// User has been on more than five LANs
		if ($this->lanCount >= 5)
			$badges[] = new Badge(Badge::BADGE_MINIMUM_5_LANS);
		
		// User has been on more than 10 LANs
		if ($this->lanCount >= 10)
			$badges[] = new Badge(Badge::BADGE_MINIMUM_10_LANS);

		$allCornerLans = true; // User has attended all Cornern LANs
		$allLans = true; // User has attended all LANs

		$attendedLans = $this->lans;

		// Determine allCornerLans and allLans
		foreach (Lan::model()->findAll() as $lan)
		{
			// Skip LANs that have not yet ended
			if (time() < strtotime($lan->end_date))
				continue;

			// Skip Assembly, it doesn't count
			if ($lan->location == Lan::LOCATION_HARTWALL)
				continue;

			if ($allLans && !in_array($lan, $attendedLans))
				$allLans = false;

			if ($lan->location == Lan::LOCATION_CORNER)
				if ($allCornerLans && !in_array($lan, $attendedLans))
					$allCornerLans = false;
		}
		
		if ($allLans)
			$badges[] = new Badge(Badge::BADGE_ALL_LANS);

		if ($allCornerLans)
			$badges[] = new Badge(Badge::BADGE_ALL_CORNER_LANS);
		
		// Has been to Assembly
		foreach ($attendedLans as $lan)
			if ($lan->location == Lan::LOCATION_HARTWALL)
				$badges[] = new Badge(Badge::BADGE_ASSEMBLY);
		
		// Never showed badge
		foreach ($this->registrations as $registration)
		{
			if ($registration->never_showed)
			{
				$badges[] = new BadgeNeverShowed($registration->lan->name);

				break;
			}
		}
		
		// User has at least one submission
		if ($this->submissionCount != 0)
			$badges[] = new Badge(Badge::BADGE_HAS_SUBMISSION);

		// User has at least one winning submission. The badge will not be 
		// shown if the competitions deadline isn't due.
		if (count($this->getWonCompetitions()) > 0)
			$badges[] = new Badge(Badge::BADGE_HAS_WINNING_SUBMISSION);

		// User has won at least one competition (excluding those that have 
		// submissions)
		$with = array(
			'registration'=>array(
				'select'=>false,
				'condition'=>'registration.user_id = :user_id',
				'params'=>array(':user_id'=>$this->id)));

		$wins = ActualCompetitor::model()->with($with)->findAllByAttributes(
				array('position'=>1));

		if (count($wins) > 0)
			$badges[] = new Badge(Badge::BADGE_WINNER);
		
		return $badges;
	}
	
	/**
	 * Returns the user's "LAN efficiency" as a percentage. The efficiency is 
	 * how many out of the last two years' LANs the user has visited (not counting 
	 * Assembly).
	 * @return float the efficiency
	 */
	public function getLanEfficiency()
	{
		$lanCondition = 'location != :location AND start_date >= DATE_SUB(CURDATE(), INTERVAL 2 YEAR)';
		$lanParams = array(':location'=>Lan::LOCATION_HARTWALL);

		$with = array(
			'lan'=>array(
				'select'=>false,
				'condition'=>$lanCondition,
				'params'=>$lanParams));

		// Find the number of LANs that have been held the last two years
		$totalLans = Lan::model()->findAll(array(
			'condition'=>$lanCondition,
			'params'=>$lanParams));

		// Find the number of registrations the user had in the same period
		$registrations = Registration::model()->with($with)->findAll(array(
			'condition'=>'user_id = :user_id',
			'params'=>array(':user_id'=>$this->id)));

		// And compare them
		return count($registrations) / count($totalLans) * 100;
	}
	
	/**
	 * Returns the URL to the user's profile picture (or a placeholder if one 
	 * doesn't exist)
	 * @return string the URL
	 */
	public function getProfileImageUrl()
	{
		if ($this->image !== null)
			return Yii::app()->image->getURL($this->image->id, 'profile');
		else
			return Yii::app()->baseUrl.'/files/images/icons/missing-profile-picture.png';
	}
	
	/**
	 * Returns an array of all users' names which can be used e.g. as source 
	 * for a typeahead field
	 * @return array
	 */
	public function getTypeaheadData()
	{
		$models = self::model()->findAll();
		$data = array();

		foreach ($models as $model)
			$data[] = $model->name;

		return $data;
	}
	
	/**
	 * Checks if the user has a valid payment for the current LAN
	 * @param Lan $lan the LAN which the payment should be valid for. If not 
	 * specified the current LAN will be used.
	 * @return boolean
	 */
	public function hasValidPayment($lan = null)
	{
		if ($lan === null)
			$lan = Lan::model()->getCurrent();

		// Check for standard payments
		foreach ($this->payments as $payment)
			if ($payment->season_id == $lan->season_id || $payment->lan_id == $lan->id)
				return true;

		// Current board members don't have to pay
		if (CommitteeMember::model()->isCurrent($this->id))
			return true;

		// Check if the user was on the board when the current season started
		if (CommitteeMember::model()->wasDuring($this->id, $lan->season->start_year))
			return true;

		return false;
	}

}