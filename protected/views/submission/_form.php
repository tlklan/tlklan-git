<?php 

// Create a list of competitions
$competitionList = CHtml::listData($competitions, 'id', 'full_name');
$registrationList = CHtml::listData($registrations, 'id', 'nick');
	
// Render the form
echo '<hr />';

$form = $this->beginWidget('TbActiveForm', array(
	'id'=>'submission-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); 

/* @var $form TbActiveForm */
echo $form->errorSummary($model);
echo $form->dropDownListRow($model, 'compo_id', $competitionList);
echo $form->dropDownListRow($model, 'submitter_id', $registrationList);
echo $form->textFieldRow($model, 'name');
echo $form->fileFieldRow($model, 'file');
echo $form->textAreaRow($model, 'comments');

?>
<div class="form-actions">
	<?php
	
	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>'Lämna in'
	));
	
	echo ' ';

	$this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'reset',
		'label'=>'Töm formuläret'
	));
	
	?>
</div>
<?php $this->endWidget(); ?>