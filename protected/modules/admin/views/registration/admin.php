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
		array(
			'name'=>'name',
			'type'=>'raw',
			// show "has not payed" icon
			'value'=>'$data->name." ".(!$data->user->hasValidPayment() ? CHtml::image(Yii::app()->baseUrl."/files/images/icons/no_can_has_pay.png") : "")',
		),
		'email',
		'nick',
		'device',
		'date',
		array(
			'class'=>'TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));