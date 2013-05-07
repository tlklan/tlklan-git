<?php

/**
 * Special case of InputColumn which renders a drop-down list (with values 
 * provided by $listData) where the cell value is pre-selected
 *
 * @author Sam
 */
class DropdownInputColumn extends InputColumn
{

	/**
	 * @var array the list data for the dropdown list
	 */
	public $listData = array();

	protected function renderDataCellContent($row, $data)
	{
		unset($row); // suppress unused variable warning
		
		$htmlOptions = array_merge($this->htmlOptions, array('prompt'=>''));

		echo CHtml::dropDownList($this->getInputAttributeName($data), 
				$this->getInputValue($data), $this->listData, $htmlOptions);
	}

}