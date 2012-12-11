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
 * @property int $is_founder
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
			$this->addError('email', "Din e-postadress eller ditt nickname finns redan");
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
			$this->addError($attribute, 'Felaktigt lösenord');
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'lanCount'=>array(self::STAT, 'Lan', 'tlk_registrations(lan_id, user_id)'),
			'submissions'=>array(self::HAS_MANY, 'Submission', 'user_id'),
			'submissionCount'=>array(self::STAT, 'Submission', 'user_id'),
			'registrations'=>array(self::HAS_MANY, 'Registration', 'user_id'),
			'lans'=>array(self::HAS_MANY, 'Lan', array('lan_id'=>'id'), 'through'=>'registrations'),
			// the following relation is only used as an intermediate to get the 
			// competitions relation
			'competitors'=>array(self::HAS_MANY, 'Competitor', array('id'=>'registration_id'), 'through'=>'registrations'),
			'competitions'=>array(self::HAS_MANY, 'Competition', array('competition_id'=>'id'), 'through'=>'competitors'),
			'image'=>array(self::HAS_ONE, 'Image', array('id'=>'image_id')),
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
			'nick'=>'Nick',
			'profileImage'=>'Profilbild',
			'password'=>'Lösenord',
			'currentPassword'=>'Nuvarande lösenord',
			'newPassword'=>'Nytt lösenord',
			'passwordRepeat'=>'Nytt lösenord (igen)',
			'has_werket_login'=>$this->scenario == 'update-admin' ? 'Har werket.tlk.fi konto' : 'Jag har ett konto på werket.tlk.fi',
			'date_added'=>'Registrerad sen',
			'removeProfileImage'=>'Ta bort min nuvarande profilbild',
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
	 * a vote has taken place will be included.
	 * @param boolean $withDeadlines don't include competitions which deadlines 
	 * haven't passed
	 * @return Competititon[]
	 */
	public function getWonCompetitions($withDeadlines = true)
	{
		$wonCompetitions = array();

		foreach ($this->competitions as $competition)
		{
			$winner = SubmissionVote::model()->getWinningSubmission($competition->id);

			if ($winner !== null && $winner->user_id == $this->id)
			{
				$competition = $winner->competition;

				if ($withDeadlines && strtotime($competition->deadline) > time())
					continue;

				$wonCompetitions[] = $competition;
			}
		}

		return $wonCompetitions;
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
	 * Returns true if the user is currently on the committee
	 * @return boolean
	 */
	private function isCurrentCommitteeMember()
	{
		// Find all committee members and check one by one
		$currentMembers = CommitteeMember::model()->getCurrentCommitteeMembers();

		foreach ($currentMembers as $member)
			if ($member->user_id == $this->id)
				return true;

		return false;
	}
	
	/**
	 * Returns an array of badges that the user has earned
	 * @return Badge[] the user's badges
	 */
	public function getBadges()
	{
		$badges = array();

		// User is a current committee member
		if ($this->isCurrentCommitteeMember())
			$badges[] = new Badge(Badge::BADGE_IS_CURRENT_COM_MEMBER);

		// User has been a committee member
		$maxYear = Yii::app()->db->createCommand('SELECT MAX(`year`) FROM tlk_committee')->queryScalar();
		
		$committeeMember = CommitteeMember::model()
				->find('user_id = :id AND `year` < :maxYear', 
						array(':id'=>$this->id, ':maxYear'=>$maxYear));

		if ($committeeMember !== null)
			$badges[] = new Badge(Badge::BADGE_FORMER_COM_MEMBER);
		
		// Is founding father?
		if ($this->is_founder)
			$badges[] = new Badge(Badge::BADGE_IS_FOUNDING_FATHER);
		
		// User has been on more than five LANs
		if ($this->lanCount >= 5)
			$badges[] = new Badge(Badge::BADGE_MANY_LANS);

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
		
		// Never showed badge
		foreach ($this->registrations as $registration)
		{
			if ($registration->never_showed)
			{
				$badges[] = new Badge(Badge::BADGE_NEVER_SHOWED,
								array('lan'=>$registration->lan->name));

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

		return $badges;
	}
	
	/**
	 * Returns the URL to the user's profile picture (or a placeholder if one 
	 * doesn't exist)
	 * @return string the URL
	 */
	public function getProfileImageUrl()
	{
		if ($this->image !== null)
			return Yii::app()->image->getURL($this->image->id, 'small');
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
	 * @return boolean
	 */
	public function hasValidPayment()
	{
		// Committee members don't have to pay
		if ($this->isCurrentCommitteeMember())
			return true;

		// Check for valid payments
		$lan = Lan::model()->getCurrent();

		$payment = Payment::model()->find('user_id = :user_id AND (season_id = :season_id OR lan_id = :lan_id)', array(
			':user_id'=>$this->id,
			':season_id'=>$lan->season_id,
			':lan_id'=>$lan->id,
				));

		return $payment !== null;
	}

}