<?php

/**
 * Represents a badge
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class Badge extends CModel
{
	// Available badges
	const BADGE_MINIMUM_5_LANS			= 'minimum_5_lans';
	const BADGE_HAS_SUBMISSION			= 'has_submission';
	const BADGE_HAS_WINNING_SUBMISSION	= 'has_winning_submission';
	const BADGE_ALL_LANS				= 'all_lans';
	const BADGE_ALL_CORNER_LANS			= 'all_corner_lans';
	const BADGE_IS_FOUNDING_FATHER		= 'is_founding_father';
	const BADGE_IS_HONORARY_MEMBER		= 'is_honorary_member';
	const BADGE_IS_CURRENT_COM_MEMBER	= 'is_current_com_member';
	const BADGE_FORMER_COM_MEMBER		= 'former_com_member';
	const BADGE_NEVER_SHOWED			= 'never_showed';
	const BADGE_MINIMUM_10_LANS			= 'minimum_10_lans';
	const BADGE_LAN_EFFICIENCY			= 'lan_efficiency';
	const BADGE_WINNER					= 'winner';
	const BADGE_ASSEMBLY				= 'assembly';

	/**
	 * @var int the type of the badge
	 */
	private $_type;

	/**
	 * Class constructor. It sets the badge type
	 * @param int $type
	 */
	public function __construct($type)
	{
		$this->_type = $type;
	}

	/**
	 * @return array the attribute names of this model
	 */
	public function attributeNames()
	{
		return array('type', 'icon', 'description');
	}

	/**
	 * Returns the icon filename for this badge
	 * @return string
	 */
	public function getIcon()
	{
		switch ($this->_type)
		{
			case self::BADGE_HAS_SUBMISSION:
				return 'has_submission.png';
			case self::BADGE_HAS_WINNING_SUBMISSION:
				return 'winning_submission.png';
			case self::BADGE_ALL_LANS:
				return 'alllans.png';
			case self::BADGE_ALL_CORNER_LANS:
				return 'all_corner_lans.png';
			case self::BADGE_IS_FOUNDING_FATHER:
				return 'founding_father.png';
			case self::BADGE_IS_HONORARY_MEMBER:
				return 'honorary_member.png';
			case self::BADGE_IS_CURRENT_COM_MEMBER:
			case self::BADGE_FORMER_COM_MEMBER:
				return 'committee_member.png';
			case self::BADGE_MINIMUM_10_LANS:
				return 'minimum_10_lans.png';
			case self::BADGE_WINNER:
				return 'winner.png';
			case self::BADGE_ASSEMBLY:
				return 'assembly.png';
			default:
				return 'default.png';
		}
	}

	/**
	 * Returns the description for this badge
	 * @return string
	 */
	public function getDescription()
	{
		switch ($this->_type)
		{
			case self::BADGE_MINIMUM_5_LANS:
				return Yii::t('badge', 'Har varit på mer än 5 LAN');
			case self::BADGE_MINIMUM_10_LANS:
				return Yii::t('badge', 'Har varit på mer än 10 LAN');
			case self::BADGE_HAS_SUBMISSION:
				return Yii::t('badge', 'Har submittat minst en entry');
			case self::BADGE_HAS_WINNING_SUBMISSION:
				return Yii::t('badge', 'Har submittad minst en vinnande entry');
			case self::BADGE_ALL_LANS:
				return Yii::t('badge', 'Har varit på varenda LAN');
			case self::BADGE_ALL_CORNER_LANS:
				return Yii::t('badge', 'Har varit på samtliga Corner-LAN');
			case self::BADGE_IS_FOUNDING_FATHER:
				return Yii::t('badge', 'Har varit med och grundat LAN-klubben');
			case self::BADGE_IS_CURRENT_COM_MEMBER:
				return Yii::t('badge', 'Sitter för tillfället i LAN-klubbens styrelse');
			case self::BADGE_FORMER_COM_MEMBER:
				return Yii::t('badge', 'Har tidigare suttit i LAN-klubbens styrelse');
			case self::BADGE_IS_HONORARY_MEMBER:
				return Yii::t('badge', 'Är hedersmedlem i LAN-klubben');
			case self::BADGE_WINNER:
				return Yii::t('badge', 'Har vunnit minst en tävling');
			case self::BADGE_ASSEMBLY:
				return Yii::t('badge', 'Har varit på Assembly (räknas endast om man anmält sig via oss)');
			default:
				return '';
		}
	}
	
	/**
	 * This method provides a way for a badge to determine whether the user 
	 * is eligible for it or not. This can be used for badges that require 
	 * complex logic to be determined and thus can't be done easily in 
	 * User::getBadges()
	 * @param User $user the user to evaluate
	 * @return boolean whether the user is eligible for the badge or not
	 */
	public static function isEligible($user)
	{
		return true;
	}

}
