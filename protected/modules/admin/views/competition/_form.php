<?php

/* @var $this AdminCompetitionController */
/* @var $model Competition */
/* @var $form TbActiveForm */

$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
));

?>
<div class="competition-form">
	<?php

	echo $form->dropDownListRow($model, 'lan_id', Lan::model()->getListData());
	echo $form->textFieldRow($model, 'display_order', array('class'=>'span1'));
	echo $form->textFieldRow($model, 'short_name');
	echo $form->textFieldRow($model, 'full_name');

	?>
	<div class="checkboxes">
		<?php echo $form->checkBoxRow($model, 'votable'); ?>
		<?php echo $form->checkBoxRow($model, 'signupable'); ?>
	</div>
	<?php

	echo $form->textFieldRow($model, 'deadline', array(
		'hint'=>'Format: YYYY-MM-DD HH:MM:SS'));

	?>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'icon'=>'ok white',
			'label'=>$model->isNewRecord ? 'Skapa' : 'Uppdatera',
		)); ?>
		&nbsp;&nbsp;&nbsp;
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'link',
			'icon'=>'remove',
			'label'=>'Avbryt',
			'url'=>$this->createUrl('admin'),
		)); ?>
	</div>
</div>

<?php $this->endWidget(); ?>