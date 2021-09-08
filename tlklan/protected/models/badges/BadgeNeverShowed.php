<?php

/**
 * Represents a "never showed" bdage
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class BadgeNeverShowed extends Badge
{

	/**
	 * @var string the name of the LAN where the user didn't show up
	 */
	private $_lanName;

	/**
	 * Class constructor.
	 * @param type $lanName
	 */
	public function __construct($lanName)
	{
		$this->_lanName = $lanName;

		parent::__construct(Badge::BADGE_NEVER_SHOWED);
	}

	/**
	 * Returns the icon filename for this badge
	 * @return string
	 */
	public function getIcon()
	{
		return 'never_showed.png';
	}

	/**
	 * Returns the description for this badge
	 * @return string
	 */
	public function getDescription()
	{
		return Yii::t('badge', 'Har anmält sig till ett LAN men inte dykt upp <span class="lan-name">({lanName})</span>', array('{lanName}'=>$this->_lanName));
	}

}