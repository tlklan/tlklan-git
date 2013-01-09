<?php

/* @var $this SuggestionController */
/* @var $model Suggestion */
$this->pageTitle = 'Uppdatera förslag';
$this->breadcrumbs = array(
	'Förslag'=>array('create'),
	'Uppdatera '.$model->name,
);

?>

<h1>Uppdatera förslaget <i><?php echo $model->name; ?></i></h1>

<hr />
<?php echo $this->renderPartial('_form', array('model'=>$model));