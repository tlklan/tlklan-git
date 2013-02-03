<?php 

/* @var $form TbActiveForm */
/* @var $model Lan */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'lan-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>false));

echo $form->textFieldRow($model, 'name', array('maxlength'=>20));
echo $form->dropDownListRow($model, 'season_id', Season::model()->getDropdownListOptions());
echo $form->textFieldRow($model, 'reg_limit', array('class'=>'span1'));
echo $form->textFieldRow($model, 'start_date', array('hint'=>'Format: YYYY-MM-DD'));
echo $form->textFieldRow($model, 'end_date', array('hint'=>'Format: YYYY-MM-DD'));
echo $form->dropdownListRow($model, 'location', $model->getLocationList());
echo $form->checkboxRow($model, 'enabled');

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'icon'=>'ok white',
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

<?php $this->endWidget();