<?php
$this->breadcrumbs=array(
	'Lans',
);

$this->menu=array(
	array('label'=>'Create Lan','url'=>array('create')),
	array('label'=>'Manage Lan','url'=>array('admin')),
);
?>

<h1>Lans</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
