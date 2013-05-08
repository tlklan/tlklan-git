<?php

/* @var $this SuggestionController */
/* @var $model Suggestion */
$this->pageTitle = Yii::t('suggest-competiton', 'Föreslå en tävling');
$this->breadcrumbs = array(
	Yii::t('suggest-competiton', 'Tävlingar')=>'#',
	Yii::t('suggest-competiton', 'Föreslå en tävling'),
);

?>
<h1><?php echo Yii::t('suggest-competiton', 'Föreslå en tävling'); ?></h1>

<?php $this->widget('cms.widgets.CachedCmsBlock',array('name'=>'suggest-info')); ?>

<hr />

<?php $this->renderPartial('_form', array(
	'model'=>$model,
)); ?>

<hr />

<h2><?php echo Yii::t('suggest-competiton', 'Förslag'); ?></h2>

<?php 

// Determine which action buttons should be displayed
if (Yii::app()->user->isAdmin())
	$template = '{update} {delete} {upVote}';
else
	$template = '{update} {upVote}';

$this->widget('TbGridView', array(
	'type'=>'striped bordered',
	'dataProvider'=>$model->search(),
	'enableSorting'=>false,
	'template'=>'{items}',
	'columns'=>array(
		'name',
		'creator.nick',
		array(
			'name'=>'mangledDescription',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'max-width: 500px;'),
		),
		'created',
		'voteCount',
		array(
			'class'=>'TbButtonColumn',
			'buttons'=>array(
				'upVote'=>array(
					'label'=>Yii::t('suggest-competiton', 'Rösta'),
					'icon'=>'thumbs-up',
					'url'=>'Yii::app()->controller->createUrl("upvoteSuggestion", array("id"=>$data->id))',
				),
				'update'=>array(
					// only displayed the update button if the user is allowed 
					// to edit the suggestion
					'visible'=>'$data->isOwner(Yii::app()->user->getUserId())',
				),
			),
			'template'=>$template,
		)
	),
));