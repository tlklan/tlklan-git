<?php

$this->pageTitle = 'Uppdatera betalning';
$this->breadcrumbs=array(
	'Betalningar'=>array('admin'),
	'Uppdatera betalning',
);
?>

<h1>Uppdatera betalning</h1>

<hr />

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'lanListData'=>$lanListData));