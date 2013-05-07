<?php

/**
 * Special case of InputColumn which renders the value inside a text field
 *
 * @author Sam
 */
class TextInputColumn extends InputColumn
{

	protected function renderDataCellContent($row, $data)
	{
		unset($row); // suppress unused variable warning

		echo CHtml::textField($this->getInputAttributeName($data), 
				$this->getInputValue($data), $this->getHtmlOptions());
	}

}