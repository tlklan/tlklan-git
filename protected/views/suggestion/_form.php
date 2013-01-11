<?php

/* @var $form TbActiveForm */
$form = $this->createWidget('TbActiveForm', array(
	'type'=>'horizontal',
));

echo $form->textFieldRow($model, 'name', array('class'=>'span4'));

echo $form->textAreaRow($model, 'description', array(
	'class'=>'span4', 
	'style'=>'min-height: 150px;',
	'hint'=>'<i>Förklara kort vad tävlingen skulle gå ut på. Om du vill utveckla ditt svar vid ett senare tillfälle kan du ändra beskrivningen genom att trycka på penikonen i listan nedan.</i>'));

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>$model->isNewRecord ? 'Lämna in förslaget' : 'Uppdatera',
	)); ?>&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('/suggestion/create'),
	)); ?>
</div>