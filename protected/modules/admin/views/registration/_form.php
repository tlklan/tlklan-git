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

$this->renderPartial('//registration/_deviceList', array(
	'form'=>$form,
	'model'=>$model,
));

echo $form->checkBoxListRow($model, 'competitions', CHtml::listData($competitions, 'id', 'full_name'));
echo $form->checkBoxRow($model, 'never_showed');

?>
<div class="form-actions">
	<?php

	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'edit white',
		'label'=>'Uppdatera'
	));

	echo ' ';

	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('registration/admin'),
	));
	
	?>
</div>

<?php $this->endWidget(); ?>