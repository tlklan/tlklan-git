<?php

/* @var $lan Lan */
/* @var $model Timetable */
/* @var $dates DateTime[] */

?>
<h1>
	Tidtabell för <?php echo $lan->name; ?>
</h1>

<p>
	Här kan du ändra på tidtabellen för <?php echo $lan->name; ?>.
</p>

<?php

$dateFormatter = Yii::app()->dateFormatter;

foreach ($dates as $i => $date)
{
	$dateString = ucfirst($dateFormatter->format("EEEE d.M.yyyy", $date->format('r')));
	
	echo CHtml::openTag('div', array('class'=>'timetable-date'));
	echo CHtml::tag('h4', array(), $dateString);

	$this->renderPartial('_timetableDate', array(
		'lan'=>$lan,
		'date'=>$date,
		'model'=>$model,
	));

	echo CHtml::closeTag('div');
}