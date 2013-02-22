<?php

// Use different words here and there depending on scenario
$heading = $model->isNewRecord ? Yii::t('registration', 'Anmäl dig') : Yii::t('registration', 'Uppdatera anmälan');

?>
<div class="span5">
	<h1><?php echo $heading; ?></h1>

	<?php 

	if ($model->isNewRecord) 
	{
		echo CHtml::opentag('p');
		echo Yii::t('registration', 'Fyll i formuläret nedan för att 
			anmäla dig till {lanName}. Om du är osäker på om du tänker delta 
			i en tävling eller inte, anmäl dig ändå (anmälningen är inte 
			bindande)! På så vis har vi bättre koll på hur vi bör planera 
			tidtabellen.', array('{lanName}'=>$currentLan->name)); 
		echo CHtml::closeTag('p');
	}
		
	?>

	<hr />

	<?php 

	$form = $this->beginWidget('TbActiveForm', array(
		'id'=>'registration-form',
		'type'=>'horizontal',
		'enableClientValidation'=>false,
	));

	echo $form->errorSummary($model, '');

	$this->renderPartial('_deviceList', array('form'=>$form, 'model'=>$model));

	echo $form->checkBoxListRow($model, 'competitionList', CHtml::listData($currentLan->competitions, 'id', 'full_name'));
	echo $form->radioButtonListRow($model, 'penis_long_enough', 
			array('yes'=>Yii::t('general', 'Ja'), 'no'=>Yii::t('general', 'Nej')));

	?>
	<div class="form-actions">
		<?php

		if($model->isNewRecord) {
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'icon'=>'ok white',
				'label'=>Yii::t('registration', 'Anmäl dig')
			));
		}
		else {
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'icon'=>'edit white',
				'label'=>Yii::t('general', 'Uppdatera')
			));

			echo ' ';

			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'link',
				'icon'=>'remove',
				'label'=>Yii::t('general', 'Avbryt'),
				'url'=>$this->createUrl('registration/create'),
			));
		}

		?>
	</div>

	<?php $this->endWidget(); ?>
</div>