<?php

/**
 * Base-class for columns that render the cell value inside a HTML form element
 *
 * @author Sam
 */
class InputColumn extends CDataColumn
{

	/**
	 * @var array options to pass to the input element
	 */
	public $htmlOptions = array();

	/**
	 * @var array options that should always be there
	 */
	protected $defaultHtmlOptions = array(
		'style'=>'margin-bottom: 0;'
	);

	/**
	 * Returns the full htmlOptions array (merge of htmlOptions and 
	 * defaultHtmlOptions)
	 * @return array
	 */
	protected function getHtmlOptions()
	{
		return array_merge($this->defaultHtmlOptions, $this->htmlOptions);
	}

	/**
	 * Returns the form element name. Child-classes should always use this 
	 * method to determine the input name
	 * @param mixed $data the data
	 * @return string the attribute name
	 */
	protected function getInputAttributeName($data)
	{
		return get_class($data).'['.$data['id'].']['.$this->name.']';
	}

	/**
	 * Returns the value that should appear in the input. Child classes do not 
	 * have to use this method, it is provided for convenience
	 * @param mixed $data the data
	 * @return string the input value
	 */
	protected function getInputValue($data)
	{
		return $data[$this->name];
	}

}