<?php

/* @var $model CompetitionCategory */
$this->pageTitle = 'Uppdatera tävlingskategori';
$this->breadcrumbs = array(
	'Tävlingskategorier'=>array('admin'),
	'Uppdatera '.$model->name,
);

echo $this->renderPartial('_form', array('model'=>$model));