<hr />
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$dataProvider,
	'template'=>"{items}",
	'emptyText'=>'Det finns inga submissions för den här tävlingen',
	'enableSorting'=>false,
	'columns'=>array(
		array(
			'name'=>'submission.name',
			'header'=>'Namn', // TODO: Link to the submission
		),
		array(
			'name'=>'user.nick',
			'header'=>'Skapare', // TODO: Link to the submitter
		),
		'vote_count',
	),
));