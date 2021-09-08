<?php

/**
 * Renders a TextInputColumn where the value is formatted using CDateFormatter 
 * according to the current locale
 *
 * @see CDateFormatter
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class DateInputColumn extends TextInputColumn
{

	/**
	 * @var string $dateWidth width of the date pattern. Defaults to 'medium'.
	 * @see CDateFormatter::formatDateTime()
	 */
	public $dateWidth = 'medium';

	/**
	 * @var string $timeWidth width of the time pattern. Defaults to 'medium'.
	 * @see CDateFormatter::formatDateTime()
	 */
	public $timeWidth = 'medium';

	/**
	 * @var CDateFormatter the date formatter instance
	 */
	private $_formatter;

	/**
	 * Initializes the column
	 */
	public function init()
	{
		parent::init();

		$locale = CLocale::getInstance(CLocale::getCanonicalID(Yii::app()->language));
		$this->_formatter = new CDateFormatter($locale);
	}

	/**
	 * Returns the data cell content formatted according to our settings
	 * @param mixed $data the data
	 * @return string the cell content
	 */
	protected function getInputValue($data)
	{
		return $this->_formatter->formatDateTime($data[$this->name], 
				$this->dateWidth, $this->timeWidth);
	}

}