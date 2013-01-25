<?php

$this->pageTitle = Yii::t('submission', 'Uppdatera submission');
$this->breadcrumbs=array(
	Yii::t('submission', 'Submissions')=>array('archive'),
	Yii::t('submission', 'Uppdatera submission'),
);

?>
<h1><?php echo Yii::t('submission', 'Uppdatera submission'); ?></h1>
<p>
	<?php echo Yii::t('submission', 'Fyll i fälten och klicka på "Uppdatera". Kontakta LAN-crew om din submission är större än <b>64 MiB</b>!'); ?>
</p>

<hr />

<?php 

// Display the upload form
echo $this->renderPartial('_form', array(
	'model'=>$model,
	'competitions'=>$competitions,
));