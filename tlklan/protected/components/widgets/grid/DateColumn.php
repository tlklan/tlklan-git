<?php

/**
 * Renders a date/datetime column, formatted using CDateFormatter according to 
 * the current application language
 *
 * @see CDateFormatter
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class DateColumn extends CDataColumn
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
	 * Renders the data cell contents.
	 * @param int $row the current row number
	 * @param array $data the row data
	 */
	protected function renderDataCellContent($row, $data)
	{
		unset($row); // suppress unused variable warning

		echo $this->_formatter->formatDateTime($data[$this->name], $this->dateWidth, $this->timeWidth);
	}

}