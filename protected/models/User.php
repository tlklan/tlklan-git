<?php

/**
 * This is the model class for table "tlk_users".
 *
 * The followings are the available columns in table 'tlk_users':
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property string $password
 * @property integer $has_werket_login
 * @property int $is_founder
 * @property string $date_added
 * 
 * @property int $lanCount
 * @property Submission[] $submissions
 * @property int $submissionCount
 * @property Lan[] $lans
 * @property Registration[] $registrations
 * @property Competition[] $competitions
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
			array('name, email, username', 'required'),
			array('has_werket_login', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>75),
			array('username', 'length', 'max'=>25),
			array('email', 'email'),
			
			// register new user (insert) scenario
			array('newPassword, passwordRepeat, has_werket_login', 'required', 'on'=>'insert'),
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
			'password'=>'Lösenord',
			'currentPassword'=>'Nuvarande lösenord',
			'newPassword'=>'Nytt lösenord',
			'passwordRepeat'=>'Nytt lösenord (igen)',
			'has_werket_login'=>$this->scenario == 'update-admin' ? 'Har werket.tlk.fi konto' : 'Jag har ett konto på werket.tlk.fi',
			'date_added'=>'Registrerad sen',
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
	 * Returns the amount of winning submissions this user has.
	 * @return int
	 */
	public function getWinningSubmissionCount()
	{
		$winningCount = 0;

		// TODO: Try to do this with relations
		// Get all competitions the user has participated in and get their 
		// voting result data provider
		foreach ($this->competitions as $competition)
		{
			$dataProvider = $competition->getSubmissionDataProvider();
			$data = $dataProvider->getData();

			// Compare the winning submission's user_id
			if (count($data) > 0 && $data[0]['voteCount'] > 0 && $data[0]['user_id'] == $this->id)
				$winningCount++;
		}

		return $winningCount;
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
		$currentMembers = CommitteeMember::model()->getCurrentCommitteeMembers();
		foreach ($currentMembers as $member)
		{
			if ($member->user_id == $this->id)
			{
				$badges[] = new Badge(Badge::BADGE_IS_CURRENT_COM_MEMBER);

				break;
			}
		}

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
		
		// User has at least one submission
		if ($this->submissionCount != 0)
			$badges[] = new Badge(Badge::BADGE_HAS_SUBMISSION);

		// User has at least one winning submission
		if ($this->getWinningSubmissionCount() > 0)
			$badges[] = new Badge(Badge::BADGE_HAS_WINNING_SUBMISSION);

		return $badges;
	}

}