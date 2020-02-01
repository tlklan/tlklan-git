<?php

/* @var $lan Lan */
/* @var $model Timetable */
/* @var $dates DateTime[] */

?>
<h1>
	Tidtabell för <?php echo $lan->name; ?>
</h1>

<p>
	Här kan du ändra på tidtabellen för <?php echo $lan->name; ?>. Ändringar 
	på existerande rader görs direkt i tabellerna nedan. För att spara 
	ändringarna trycker du på Spara-knappen. Om du vill lägga till en ny rad 
	trycker du på Lägg till rad knappen. <i>Observera dock att dina ändringar inte 
	kommer att sparas då du lägger till en ny rad!</i>
</p>

<hr />

<?php

$dateFormatter = Yii::app()->dateFormatter;
$this->beginWidget('TbActiveForm');

foreach ($dates as $date)
{
	$dateString = ucfirst($dateFormatter->format("EEEE d.M", $date->format('r')));
	
	echo CHtml::openTag('div', array('class'=>'timetable-date'));
	echo CHtml::tag('h4', array(), $dateString);

	$this->renderPartial('_timetableDate', array(
		'lan'=>$lan,
		'date'=>$date,
		'model'=>$model,
	));

	echo CHtml::closeTag('div');
	
	echo '<hr />';
}

$this->endWidget();