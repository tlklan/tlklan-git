<?php

$this->pageTitle = Yii::t('submission', 'Ny submission');
$this->breadcrumbs=array(
	Yii::t('submission', 'Submissions')=>array('archive'),
	Yii::t('submission', 'Ny submission'),
);

?>
<h1><?php echo Yii::t('submission', 'Ny submission'); ?></h1>

<p>
	<?php echo Yii::t('submission', 'Fyll i fälten och klicka på "Lämna in". Kontakta LAN-crew om din submission är större än <b>64 MiB</b>!'); ?>
</p>

<hr />

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'competitions'=>$competitions,
)); ?>

<div class="alert in alert-block fade alert-info">
	<?php
	
	$archiveUrl = $this->createUrl('/submission/archive');
	$archiveLink = CHtml::link(Yii::t('submission', 'arkivsidan'), $archiveUrl);
	$mailtoLink = CHtml::link(Yii::app()->params['mail']['committee'], 
							  Yii::app()->params['mail']['committee']);
	
	echo Yii::t('submission', '<b>OBS!</b> Genom att ladda upp dina filer här går du med på att de publiceras på 
	<b>{archiveLink}</b>. Om du inte vill ha din fil tillgänglig för nedladdning bör du 
	kontakta <b>{mailtoLink}</b>.', array(
		'{archiveLink}'=>$archiveLink,
		'{mailtoLink}'=>$mailtoLink,
	));
	
	?>
</div>