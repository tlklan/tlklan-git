<?php
$this->breadcrumbs=array(
	'Lans'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Lan','url'=>array('index')),
	array('label'=>'Manage Lan','url'=>array('admin')),
);
?>

<h1>Create Lan</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>