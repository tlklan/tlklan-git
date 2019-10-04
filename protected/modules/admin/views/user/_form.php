<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
)); 

echo $form->textFieldRow($model, 'name');
echo $form->textFieldRow($model, 'email');
echo $form->textFieldRow($model, 'username');
echo $form->checkboxRow($model, 'has_werket_login');
echo $form->checkboxRow($model, 'is_honorary_member');
echo $form->passwordFieldRow($model, 'newPassword');
echo $form->passwordFieldRow($model, 'passwordRepeat');

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>'Ändra',
	)); ?>
	&nbsp;&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('admin'),
	)); ?>
</div>

<?php $this->endWidget();
