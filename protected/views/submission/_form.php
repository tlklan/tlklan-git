<?php 

// Create a list of competitions
$competitionList = CHtml::listData($competitions, 'id', 'full_name');
	
// Render the form
echo '<hr />';

$form = $this->beginWidget('TbActiveForm', array(
	'id'=>'submission-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); 

/* @var $form TbActiveForm */

echo $form->dropDownListRow($model, 'compo_id', $competitionList, array('prompt'=>''));
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
		'label'=>$model->isNewRecord ? 'Lämna in' : 'Uppdatera',
	));
	
	echo '&nbsp;&nbsp;&nbsp;';

	if($model->isNewRecord) {
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'reset',
			'label'=>'Töm formuläret'
		));
	}
	else {
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'link',
			'icon'=>'remove',
			'label'=>'Avbryt',
			'url'=>$this->createUrl('/submission/archive'),
		));
	}
	
	?>
</div>
<?php $this->endWidget(); ?>