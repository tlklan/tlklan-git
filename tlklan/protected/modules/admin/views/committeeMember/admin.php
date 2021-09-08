<?php

/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */

$this->breadcrumbs = array(
	'Styrelsemedlemmar' => array('admin'),
	'Hantera',
);

$this->menu = array(
	array('label' => 'Skapa ny medlem', 'url' => array('create')),
);

?>
<h1>Hantera styrelsemedlemmar</h1>
<?php

$this->widget('TbGridView', array(
	'id'           => 'committee-member-grid',
	'type'         => 'striped bordered',
	'dataProvider' => $dataProvider,
	'filter'       => $model,
	'columns'      => array(
		array(
			'name'=>'name',
		),
		array(
			'name'=>'year',
			'type'=>'raw',
			'filter'=>CHtml::listData(CommitteeMember::model()->findAll(), 'year', 'year'),
			'value'=>'$data->year',
		),
		array(
			'name'=>'position',
			'type'=>'raw',
			'filter'=>CommitteeMember::$availablePositions,
			'value'=>function($data) {
				return CommitteeMember::$availablePositions[$data->position];
			},
		),
		array(
			'class' => 'TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
));
