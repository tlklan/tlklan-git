<?php

/* @var $this RegistrationController */
/* @var $model Registration */
$this->pageTitle = 'Hantera anmälningar';
$this->breadcrumbs = array(
	'Anmälningar'=>array('admin'),
	'Hantera anmälningar',
);

?>

<h1>Hantera anmälningar</h1>

<p>
	Härifrån kan man se vilka som är/har varit anmälda till de olika LANen. 
	Genom att klicka på ikonerna till höger i listan kan man ändra och ta bort 
	anmälningar samt lägga in betalningar för sådana som inte har en giltig 
	betalning.
</p>

<?php 

$this->renderPartial('_list', array(
	'dataProvider'=>$dataProvider,
	'model'=>$model,
));