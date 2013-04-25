<?php

/**
 * User's get this badge when they have attended all LANs within a two-year 
 * time period.
 *
 * @author Sam Stenvall <sam.stenvall@arcada.fi>
 */
class BadgeLanEfficiency extends Badge
{

	/**
	 * Override constructor so we don't have to take the $type parameter
	 */
	public function __construct()
	{
		parent::__construct(self::BADGE_LAN_EFFICIENCY);
	}

	public function getDescription()
	{
		return Yii::t('badge', 'Har någon gång haft 100% LAN-effektivitet');
	}

	public function getIcon()
	{
		return 'lan_efficiency.png';
	}

	/**
	 * Check all LANs to see if the user attended it and if his efficiency was 
	 * at 100% at the time the LAN ended
	 * @param User $user
	 * @return boolean
	 */
	public static function isEligible($user)
	{
		/* @var $user User */
		foreach (Lan::model()->findAll() as $lan)
			if (in_array($lan, $user->lans) && $user->getLanEfficiency($lan->end_date) == 100)
				return true;

		return false;
	}

}