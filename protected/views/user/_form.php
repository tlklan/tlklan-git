<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
)); 

echo $form->textFieldRow($model, 'name');
echo $form->textFieldRow($model, 'email');
echo $form->uneditableRow($model, 'username');
echo $form->textFieldRow($model, 'nick');

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>'Ändra',
	)); ?>&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('user/profile'),
	)); ?>
</div>

<?php $this->endWidget(); ?>