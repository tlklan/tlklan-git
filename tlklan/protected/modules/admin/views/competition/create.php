<?php

/* @var $this AdminCompetitionController */
/* @var $model Competition */

$this->pageTitle = 'Skapa ny tävling';
$this->breadcrumbs=array(
	'Tävlingar'=>array('admin'),
	'Skapa ny',
);

?>

<h1>Skapa ny tävling</h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>