<?php

/* @var $this AdminCompetitionController */
/* @var $model Competition */

$this->pageTitle = 'Hantera tävlingar';
$this->breadcrumbs=array(
	'Tävlingar'=>array('admin'),
	'Hantera',
);

$this->menu = array(
	array('label'=>'Skapa ny', 'url'=>array('create')),
);

?>
<h1>Hantera tävlingar</h1>

<p>
	Härifrån kan du bläddra bland samtliga tävlingar som någonsin har hållits. 
	Klicka på penikonen för att göra ändringar.
</p>

<div class="alert alert-block alert-info">
	Om du vill ändra ordningen på tävlingarna gör du det enklast genom att 
	uppdatera LANet de hör till
</div>

<?php $this->renderPartial('_adminGrid', array('model'=>$model));