<?php

$this->pageTitle = 'Hantera LAN';
$this->breadcrumbs=array(
	'LAN'=>array('admin'),
	'Hantera',
);

?>
<h1>Hantera LAN</h1>

<p>
	Härifrån kan man skapa nya LAN, ändra på dem och välja vilket som ska vara 
	aktivt.
</p>

<?php 

$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'lan-grid',
	'type'=>'striped bordered',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'name',
		array(
			'name'=>'seasonId',
            'filter'=>$seasons,
			'type'=>'raw',
            'value'=>'($data->season !== null) ? $data->season->name : ""',
		),
		'reg_limit',
		array(
			'name'=>'registrationCount',
			'filter'=>false,
		),
		'start_date',
		'end_date',
		array(
			'name'=>'location',
            'filter'=>Lan::$locationList,
			'type'=>'raw',
            'value'=>'Lan::$locationList[$data->location]',
		),
		array(
			'name'=>'enabled',
            'filter'=>array('1'=>'Ja','0'=>'Nej'),
			'type'=>'raw',
            'value'=>'($data->enabled=="1")?(\'<i class="icon-ok"></i>\'):""',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'buttons'=>array(
				'timetable'=>array(
					'label'=>'Hantera tidtabellen',
					'icon'=>'time',
					'url'=>'array("timetable/admin", "lanId"=>$data->id)',
				),
			),
			'template'=>'{timetable} {update} {delete}',
		),
	),
));