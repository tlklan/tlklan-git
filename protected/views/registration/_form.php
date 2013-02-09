<fieldset> 
    <legend><?php echo Yii::t('registration', 'Anmäl dig'); ?></legend>
	<?php 

	$form = $this->beginWidget('TbActiveForm', array(
		'id'=>'registration-form',
		'type'=>'horizontal',
		'enableClientValidation'=>false,
	));

	echo $form->errorSummary($model, '');

	echo $form->uneditableRow($model, 'name');
	echo $form->uneditableRow($model, 'email');
	echo $form->uneditableRow($model, 'nick');
	
	$this->renderPartial('_deviceList', array('form'=>$form, 'model'=>$model));

	echo $form->checkBoxListRow($model, 'competitions', CHtml::listData($competitions, 'id', 'full_name'));
	echo $form->radioButtonListRow($model, 'penis_long_enough', 
			array('yes'=>Yii::t('general', 'Ja'), 'no'=>Yii::t('general', 'Nej')));

	?>
	<div class="form-actions">
		<?php

		if($registration->isNewRecord) {
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'icon'=>'ok white',
				'label'=>Yii::t('registration', 'Anmäl dig')
			));

			echo ' ';

			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'reset',
				'label'=>Yii::t('general', 'Töm formuläret')
			));
		}
		else {
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'icon'=>'edit white',
				'label'=>Yii::t('general', 'Uppdatera')
			));

			echo ' ';

			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'link',
				'icon'=>'remove',
				'label'=>Yii::t('general', 'Avbryt'),
				'url'=>$this->createUrl('registration/create'),
			));
		}

		?>
	</div>

	<?php $this->endWidget(); ?>
</fieldset>