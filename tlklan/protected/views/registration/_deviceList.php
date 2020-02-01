<?php

/* @var $form TbActiveForm */
/* @var $model Registration */

?>
<div class="control-group">
	<?php echo $form->labelEx($model, 'device', array('class'=>'control-label')); ?>
	
	<div class="controls">
		<?php
		
		$attribute = 'device';
		$i = 0;
		
		foreach(Device::getSelectableDevices() as $value => $name) 
		{
			// Determine initial htmlOptions for the label and radiobutton
			$htmlOptions = array();
			CHtml::resolveNameID($model, $attribute, $htmlOptions);

			// Make a different set of options for the radio button
			$htmlOptions['id'] .= '_'.$i;
			$radioHtmlOptions = $htmlOptions;
			$radioHtmlOptions['value'] = $value;
			$radioHtmlOptions['uncheckValue'] = null;

			// Check the used device
			if ($model->device)
				$radioHtmlOptions['checked'] = $value == $model->device;

			// Disable the iPad option
			if ($value == 'ipad')
				$radioHtmlOptions['disabled'] = true;
			
			?>
			<label class="radio">
				<?php echo $form->radioButton($model, 'device', $radioHtmlOptions); ?>
				<?php echo CHtml::label($name, $htmlOptions['id'], $htmlOptions); ?>
			</label>
			<?php
			
			$i++;
		}
		
		?>
	</div>
</div>