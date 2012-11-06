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

$this->widget('TbGridView', array(
	'id'=>'registration-grid',
	'type'=>'striped bordered',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'lanName',
		'name',
		'email',
		'nick',
		'device',
		'date',
		/*
		
		'confirmed',
		'deleted',
		*/
		array(
			'class'=>'TbButtonColumn',
			'htmlOptions'=>array(
				'style'=>'min-width: 50px;',
			),
		),
	),
));