<?php

/* @var $form TbActiveForm */
$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
));

echo $form->textFieldRow($model, 'name', array('class'=>'span4'));

echo $form->textAreaRow($model, 'description', array(
	'class'=>'span4', 
	'style'=>'min-height: 150px;',
	'hint'=>Yii::t('suggest-competiton', 'Förklara kort vad tävlingen skulle gå ut på. Om du vill utveckla ditt svar vid ett senare tillfälle kan du ändra beskrivningen genom att trycka på penikonen i listan nedan.')));

?>
<div class="form-actions">
	<?php 
	
	$this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>$model->isNewRecord ? 
			Yii::t('suggest-competiton', 'Lämna in förslaget') : 
			Yii::t('general', 'Uppdatera'),
	)); 
	
	// show cancel button when updating models
	if (!$model->isNewRecord)
	{
		echo '&nbsp;&nbsp;&nbsp;';

		$this->widget('TbButton', array(
			'buttonType'=>'link',
			'icon'=>'remove',
			'label'=>Yii::t('general', 'Avbryt'),
			'url'=>$this->createUrl('/suggestion/create'),
		));
	}
	
	?>
</div>

<?php $this->endWidget();