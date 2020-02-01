<?php

/**
 * Helper class for getting the list of valid and selectable devices (for the
 * registration form) 
 */
class Device
{

	/**
	 * @return array list of valid devices
	 */
	public static function getValidDevices()
	{
		return array(
			'desktop',
			'laptop',
			'console',
		);
	}

	/**
	 * @return array list of valid devices where the device name is the key and 
	 * the value is a human-readable version of it
	 */
	public static function getSelectableDevices()
	{
		return array(
			'desktop'=>'Desktop',
			'laptop'=>'Laptop',
			'console'=>Yii::t('device', 'Konsol'),
			'ipad'=>'iPad',
		);
	}

}