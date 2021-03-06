<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */

$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
	'htmlOptions'=>array('encType'=>'multipart/form-data'),
)); 

echo $form->textFieldRow($model, 'name');
echo $form->textFieldRow($model, 'email');
echo $form->uneditableRow($model, 'username');
echo $form->textFieldRow($model, 'nick');
echo $form->fileFieldRow($model, 'profileImage');
echo $form->checkboxRow($model, 'removeProfileImage');

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>Yii::t('general', 'Ändra'),
	)); ?>&nbsp;&nbsp;
	<?php $this->widget('TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>Yii::t('general', 'Avbryt'),
		'url'=>$this->createUrl('user/profile'),
	)); ?>
</div>

<?php $this->endWidget(); ?>