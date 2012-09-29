<?php 

// Create a list of competitions
$competitionList = CHtml::listData($competitions, 'id', 'full_name');
$registrationList = CHtml::listData($registrations, 'id', 'nick');
	
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'submission-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); 

echo $form->errorSummary($model);

?>
<div class="form grey-form">
	<table class="form-table" border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
		<tr>
			<td><?php echo $form->labelEx($model,'compo_id'); ?>:</td>
			<td><?php echo $form->dropDownList($model, 'compo_id', $competitionList, array('prompt'=>'')); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'submitter_id'); ?>:</td>
			<td><?php echo $form->dropDownList($model, 'submitter_id', $registrationList, array('prompt'=>'')); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'name'); ?>:</td>
			<td><?php echo $form->textField($model,'name'); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'file'); ?>:</td>
			<td><?php echo $form->fileField($model,'file'); ?></td>
		</tr>
		<tr>
			<td><?php echo $form->labelEx($model,'comments'); ?>:</td>
			<td><?php echo $form->textArea($model,'comments',array('rows'=>'10', 'cols'=>'40', 'style'=>'width: 100%;')); ?></td>
		</tr>
		
		<tr>
			<td style="padding-top: 14px;" colspan="2">
				<?php echo CHtml::submitButton("Lämna in"); ?>
				<?php echo CHtml::resetButton("Töm formuläret"); ?>
			</td>
		</tr>
	</table>
</div>
<?php $this->endWidget(); ?>