<fieldset> 
    <legend>Anmäl dig</legend>
	<?php 

	$form = $this->beginWidget('TbActiveForm', array(
		'id'=>'registration-form',
		'type'=>'horizontal',
		'enableClientValidation'=>false,
	));

	echo $form->errorSummary($model, '');

	echo $form->textFieldRow($model, 'name');
	echo $form->textFieldRow($model, 'email');
	echo $form->textFieldRow($model, 'nick');
	echo $form->radioButtonListRow($model, 'device', array(
		'desktop'=>'Desktop', 
		'laptop'=>'Laptop',
		'ipad'=>'iPad',
	));

	echo $form->checkBoxListRow($model, 'competitions', CHtml::listData($competitions, 'id', 'full_name'));
	echo $form->radioButtonListRow($model, 'penis_long_enough', array('yes'=>'Ja', 'no'=>'Nej'));

	?>
	<div class="form-actions">
		<?php

		if($registration->isNewRecord) {
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'icon'=>'ok white',
				'label'=>'Anmäl dig'
			));

			echo ' ';

			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'reset',
				'label'=>'Töm formuläret'
			));
		}
		else {
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'icon'=>'edit white',
				'label'=>'Uppdatera'
			));

			echo ' ';

			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'icon'=>'remove',
				'label'=>'Avbryt',
				'htmlOptions'=>array(
					'name'=>'cancel',
				)
			));
		}

		?>
	</div>

	<?php $this->endWidget(); ?>
</fieldset>