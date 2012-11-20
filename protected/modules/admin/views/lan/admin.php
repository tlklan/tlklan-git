<?php

$this->pageTitle = 'Hantera LAN';
$this->breadcrumbs=array(
	'LAN'=>array('admin'),
	'Hantera',
);

?>
<h1>Hantera LAN</h1>

<p>
	Härifrån kan man skapa nya LAN, ändra på dem och välja vilket som ska vara 
	aktivt.
</p>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'lan-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'reg_limit',
		'start_date',
		'end_date',
		array(
			'name'=>'location',
            'filter'=>$model->getLocationList(),
			'type'=>'raw',
            'value'=>'$data->getFriendlyLocation()',
		),
		array(
			'name'=>'enabled',
            'filter'=>array('1'=>'Ja','0'=>'Nej'),
			'type'=>'raw',
            'value'=>'($data->enabled=="1")?(\'<i class="icon-ok"></i>\'):""',
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));