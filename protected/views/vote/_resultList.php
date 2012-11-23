<hr />
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$dataProvider,
	'template'=>"{items}",
	'emptyText'=>'Det finns inga submissions för den här tävlingen',
	'columns'=>array(
		array(
			'name'=>'name',
			'header'=>'Namn',
			'value'=>'$data["name"]',
		),
		array(
			'name'=>'nick',
			'header'=>'Skapare',
			'value'=>'$data["nick"]',
		),
		array(
			'name'=>'voteCOunt',
			'header'=>'Antal röster',
			'value'=>'$data["voteCount"]',
		),
	),
));