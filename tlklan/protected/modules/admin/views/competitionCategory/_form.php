<?php 

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'type'=>'horizontal'));

echo $form->textFieldRow($model, 'name', array('autofocus'=>'autofocus'));

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Skapa' : 'Uppdatera',
	)); ?>&nbsp;&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('admin'),
	)); ?>
</div>

<?php $this->endWidget(); ?>
