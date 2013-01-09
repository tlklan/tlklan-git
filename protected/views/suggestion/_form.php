<?php

/* @var $form TbActiveForm */
$form = $this->createWidget('TbActiveForm', array(
	'type'=>'horizontal',
));

echo $form->textFieldRow($model, 'name', array(
	'class'=>'span4',
	'hint'=>'<i>Namn på tävlingen, inte ditt eget (eller din mors) namn</i>'));

echo $form->textAreaRow($model, 'description', array(
	'class'=>'span4', 
	'style'=>'min-height: 150px;',
	'hint'=>'<i>Förklara kort vad tävlingen skulle gå ut på</i>'));

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>$model->isNewRecord ? 'Lämna in förslaget' : 'Uppdatera',
	)); ?>
</div>