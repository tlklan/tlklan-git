<?php

$this->pageTitle = 'Hantera betalningar';
$this->breadcrumbs=array(
	'Betalningar'=>array('admin'),
	'Hantera',
);

$this->menu = array(
	array('label'=>'Ny betalning', 'url'=>array('create')),
);

?>

<h1>Hantera betalningar</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'payment-grid',
	'type'=>'striped bordered',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'userName',
		'lanName',
		'seasonName',
		array(
			'name'=>'type',
			'filter'=>$model->getValidTypes(),
			'type'=>'raw',
			'value'=>'$data->getType()',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));