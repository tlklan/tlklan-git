<?php

/* @var $this RegistrationController */
/* @var $model Registration */
$this->breadcrumbs = array(
	'Anmälningar'=>array('admin'),
	'Hantera anmälningar',
);

?>

<h1>Hantera anmälningar</h1>

<p>
	Härifrån kan man se vilka som är/har varit anmälda till de olika LANen. 
	Genom att klicka på ikonerna till höger i listan kan man ändra, ta bort 
	och godkänna anmälningar.
</p>

<?php 

$this->renderPartial('_list', array(
	'dataProvider'=>$dataProvider,
	'model'=>$model,
));