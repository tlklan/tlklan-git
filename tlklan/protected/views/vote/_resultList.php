<hr />
<?php

$this->widget('TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$dataProvider,
	'template'=>"{items}",
	'emptyText'=>Yii::t('vote', 'Det finns inga submissions för den här tävlingen'),
	'enableSorting'=>false,
	'rowCssClassExpression'=>'$data->submission->isWinner() ? "winning-submission" : ""',
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>Yii::t('vote', 'Namn'),
			'labelExpression'=>'$data->submission->name',
			'urlExpression'=>'Yii::app()->controller->createUrl("/submission/archive")."#".$data->submission_id',
		),
		array(
			'class'=>'CLinkColumn',
			'header'=>Yii::t('vote', 'Skapare'),
			'labelExpression'=>'$data->user->nick',
			'urlExpression'=>'Yii::app()->controller->createUrl("/user/profile", array("id"=>$data->user->id))',
		),
		'vote_count',
	),
));