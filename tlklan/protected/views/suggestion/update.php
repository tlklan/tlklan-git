<?php

/* @var $this SuggestionController */
/* @var $model Suggestion */
$this->pageTitle = Yii::t('suggest-competiton', 'Uppdatera förslag');
$this->breadcrumbs = array(
	Yii::t('suggest-competiton', 'Förslag')=>array('create'),
	Yii::t('suggest-competiton', 'Uppdatera {name}', array('{name}'=>$model->name)),
);

?>

<h1><?php echo Yii::t('suggest-competiton', 'Uppdatera förslaget <i>{name}</i>', array('{name}'=>$model->name)); ?></h1>

<hr />
<?php echo $this->renderPartial('_form', array('model'=>$model));