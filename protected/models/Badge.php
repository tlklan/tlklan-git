<?php

/**
 * Represents a badge
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class Badge extends CModel
{
	// Define badge types

	const BADGE_MANY_LANS				= 0;
	const BADGE_HAS_SUBMISSION			= 1;
	const BADGE_HAS_WINNING_SUBMISSION	= 2;
	const BADGE_ALL_LANS				= 4;
	const BADGE_ALL_CORNER_LANS			= 8;

	/**
	 * @var int the type of the badge
	 */
	private $_type;

	/**
	 * Class constructor. It sets the badge type
	 * @param type $type
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
			case self::BADGE_HAS_WINNING_SUBMISSION:
				return 'winning_submission.png';
				break;
			case self::BADGE_ALL_LANS:
				return 'alllans.png';
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
			case self::BADGE_MANY_LANS:
				return 'Har varit på mer än 5 LAN';
				break;
			case self::BADGE_HAS_SUBMISSION:
				return 'Har submittat minst en entry';
				break;
			case self::BADGE_HAS_WINNING_SUBMISSION:
				return 'Har submittad minst en vinnande entry';
				break;
			case self::BADGE_ALL_LANS:
				return 'Har varit på varenda LAN';
				break;
			case self::BADGE_ALL_CORNER_LANS:
				return 'Har varit på samtliga Corner-LAN';
				break;
			default:
				return '';
		}
	}

}