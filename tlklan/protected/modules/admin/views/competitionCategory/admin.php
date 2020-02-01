<?php

/* @var $model CompetitionCategory */
$this->pageTitle = 'Tävlingskategorier';
$this->breadcrumbs = array(
	'Tävlingskategorier',
);

$this->menu = array(
	array('label'=>'Skapa ny kategori', 'url'=>array('create')),
);

?>
<h1>Hantera tävlingskategorier</h1>
<?php

$this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'competition-category-grid',
	'dataProvider'=>$model->search(),
	'columns'=>array(
		'name',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}'
		),
	),
));