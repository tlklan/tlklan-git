<?php

/* @var $model Timetable */
/* @var $lan Lan */
/* @var $date DateTime */

$this->widget('TbGridView',array(
	'type'=>'striped bordered',
	'dataProvider'=>Timetable::model()->search($lan->id, $date),
	'template'=>'{items}',
	'columns'=>array(
		array(
			'class'=>'DateColumn',
			'dateWidth'=>null,
			'timeWidth'=>'short',
			'name'=>'start_time',
		),
		array(
			'class'=>'DateColumn',
			'dateWidth'=>null,
			'timeWidth'=>'short',
			'name'=>'end_time',
		),
		'name',
	),
));