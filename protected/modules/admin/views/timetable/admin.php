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

foreach ($dates as $i => $date)
{
	echo CHtml::openTag('div', array('class'=>'timetable-date'));
	echo CHtml::tag('h4', array(), $date->format('r'));

	$this->renderPartial('_timetableDate', array(
		'lan'=>$lan,
		'date'=>$date,
		'model'=>$model,
	));

	echo CHtml::closeTag('div');
}