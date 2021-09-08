<?php

/* @var $model CompetitionCategory */
$this->pageTitle = 'Skapa ny tävlingskategori';
$this->breadcrumbs = array(
	'Tävlingskategorier'=>array('admin'),
	'Skapa ny',
);

echo $this->renderPartial('_form', array('model'=>$model));