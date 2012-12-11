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
			'class'=>'CLinkColumn',
			'header'=>'Namn',
			'labelExpression'=>'$data->submission->name',
			'urlExpression'=>'Yii::app()->controller->createUrl("/submission/archive")."#".$data->submission_id',
		),
		array(
			'class'=>'CLinkColumn',
			'header'=>'Skapare',
			'labelExpression'=>'$data->user->nick',
			'urlExpression'=>'Yii::app()->controller->createUrl("/user/profile", array("id"=>$data->user->id))',
		),
		'vote_count',
	),
));