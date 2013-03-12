<?php

/**
 * Represents a badge
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class Badge extends CModel
{
	// Define badge types. The values don't mean anything for now
	const BADGE_MINIMUM_5_LANS			= 0;
	const BADGE_HAS_SUBMISSION			= 1;
	const BADGE_HAS_WINNING_SUBMISSION	= 2;
	const BADGE_ALL_LANS					= 4;
	const BADGE_ALL_CORNER_LANS			= 8;
	const BADGE_IS_FOUNDING_FATHER		= 16;
	const BADGE_IS_CURRENT_COM_MEMBER	= 32;
	const BADGE_FORMER_COM_MEMBER		= 64;
	const BADGE_NEVER_SHOWED				= 128;
	const BADGE_MINIMUM_10_LANS			= 256;

	/**
	 * @var int the type of the badge
	 */
	private $_type;
	
	/**
	 * @var array list of key->value pairs that can be used to add dynamic 
	 * content to the badge descriptions.
	 */
	private $_dynamicValues;

	/**
	 * Class constructor. It sets the badge type
	 * @param type $type
	 */
	public function __construct($type, $dynamicValues = array())
	{
		$this->_type = $type;
		$this->_dynamicValues = $dynamicValues;
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
				break;
			case self::BADGE_HAS_WINNING_SUBMISSION:
				return 'winning_submission.png';
				break;
			case self::BADGE_ALL_LANS:
				return 'alllans.png';
				break;
			case self::BADGE_ALL_CORNER_LANS:
				return 'all_corner_lans.png';
				break;
			case self::BADGE_IS_FOUNDING_FATHER:
				return 'founding_father.png';
				break;
			case self::BADGE_IS_CURRENT_COM_MEMBER:
			case self::BADGE_FORMER_COM_MEMBER:
				return 'committee_member.png';
				break;
			case self::BADGE_NEVER_SHOWED:
				return 'never_showed.png';
				break;
			case self::BADGE_MINIMUM_10_LANS:
				return 'minimum_10_lans.png';
				break;
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
				break;
			case self::BADGE_MINIMUM_10_LANS:
				return Yii::t('badge', 'Har varit på mer än 10 LAN');
				break;
			case self::BADGE_HAS_SUBMISSION:
				return Yii::t('badge', 'Har submittat minst en entry');
				break;
			case self::BADGE_HAS_WINNING_SUBMISSION:
				return Yii::t('badge', 'Har submittad minst en vinnande entry');
				break;
			case self::BADGE_ALL_LANS:
				return Yii::t('badge', 'Har varit på varenda LAN');
				break;
			case self::BADGE_ALL_CORNER_LANS:
				return Yii::t('badge', 'Har varit på samtliga Corner-LAN');
				break;
			case self::BADGE_IS_FOUNDING_FATHER:
				return Yii::t('badge', 'Har varit med och grundat LAN-klubben');
				break;
			case self::BADGE_IS_CURRENT_COM_MEMBER:
				return Yii::t('badge', 'Sitter för tillfället i LAN-klubbens styrelse');
				break;
			case self::BADGE_FORMER_COM_MEMBER:
				return Yii::t('badge', 'Har tidigare suttit i LAN-klubbens styrelse');
				break;
			case self::BADGE_NEVER_SHOWED:
				return Yii::t('badge', 'Har anmält sig till ett fullt LAN men inte dykt upp <span class="lan-name">({lanName})</span>', array('{lanName}'=>$this->_dynamicValues['lan']));
				break;
			default:
				return '';
		}
	}

}