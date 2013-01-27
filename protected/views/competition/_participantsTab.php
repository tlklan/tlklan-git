<?php

/* @var $dataProvider CActiveDataProvider */
/* @var $competition Competition */

// Only show the button column for logged in users
$columns = array('registration.nick');
if (Yii::app()->user->isAdmin())
{
	$columns[] = array(
		'class'=>'TbButtonColumn',
		'template'=>'{delete}',
	);
}

$this->widget('TbGridView', array(
	'type'=>'striped bordered condensed',
	'dataProvider'=>$dataProvider,
	'template'=>"{items}",
	'emptyText'=>Yii::t('competition', Yii::t('competition', 'Ingen har ännu anmält sig till den här tävlingen')),
	'columns'=>$columns
));