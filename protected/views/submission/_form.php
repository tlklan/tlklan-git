<?php 

/* @var $form TbActiveForm */
/* @var $model Submission */

// Create a list of competitions
$competitionList = CHtml::listData($competitions, 'id', 'full_name');
	
// Render the form
$form = $this->beginWidget('TbActiveForm', array(
	'id'=>'submission-form',
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); 

// Only allow administrators to change submitter
if (Yii::app()->user->isAdmin())
	echo $form->dropDownListRow($model, 'user_id', CHtml::listData(
			User::model()->findAll(array('order'=>'name')), 'id', 'name'));

echo $form->dropDownListRow($model, 'competition_id', $competitionList, array('prompt'=>''));
echo $form->textFieldRow($model, 'name');
echo $form->fileFieldRow($model, 'file');
echo $form->textAreaRow($model, 'comments');

?>
<div class="form-actions">
	<?php
	
	$this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>$model->isNewRecord ? Yii::t('submission', 'Lämna in') : Yii::t('submission', 'Uppdatera'),
	));
	
	echo '&nbsp;&nbsp;&nbsp;';

	if($model->isNewRecord) {
		$this->widget('TbButton', array(
			'buttonType'=>'reset',
			'label'=>Yii::t('general', 'Töm formuläret')
		));
	}
	else {
		$this->widget('TbButton', array(
			'buttonType'=>'link',
			'icon'=>'remove',
			'label'=>Yii::t('general', 'Avbryt'),
			'url'=>$this->createUrl('/submission/archive'),
		));
	}
	
	?>
</div>
<?php $this->endWidget(); ?>