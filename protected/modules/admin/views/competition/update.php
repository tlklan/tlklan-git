<?php

/* @var $this AdminCompetitionController */
/* @var $model Competition */

$this->pageTitle = 'Uppdatera '.$model->full_name;
$this->breadcrumbs=array(
	'Tävlingar'=>array('admin'),
	'Uppdatera '.$model->full_name,
);

?>

<h1>Uppdatera <?php echo $model->full_name; ?></h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model));