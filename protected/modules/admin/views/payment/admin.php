<?php

$this->pageTitle = 'Hantera betalningar';
$this->breadcrumbs=array(
	'Betalningar'=>array('admin'),
	'Hantera',
);

?>

<h1>Hantera betalningar</h1>

<hr />

<p>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'url'=>$this->createUrl('create'),
		'type'=>'primary',
		'icon'=>'pencil white',
		'label'=>'Ny betalning'
	)); ?>
</p>
<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'payment-grid',
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