<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
)); 

echo $form->passwordFieldRow($model, 'currentPassword');
echo $form->passwordFieldRow($model, 'newPassword');
echo $form->passwordFieldRow($model, 'passwordRepeat');

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>Yii::t('general', 'Ändra'),
	)); ?>&nbsp;&nbsp;&nbsp;<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>Yii::t('general', 'Avbryt'),
		'url'=>$this->createUrl('user/profile'),
	)); ?>
</div>

<?php $this->endWidget(); ?>