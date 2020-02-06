<?php

$this->pageTitle = 'Ny betalning';
$this->breadcrumbs=array(
	'Betalningar'=>array('admin'),
	'Ny betalning',
);

?>

<h1>Ny betalning</h1>

<hr />

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'lanListData'=>$lanListData));