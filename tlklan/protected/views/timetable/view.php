<?php

$title = Yii::t('Timetable', 'Tidtabell för {lanName}', array(
	'{lanName}'=>$lan->name));

$this->pageTitle = $title;
$this->breadcrumbs = array(Yii::t('Timetable', 'Tidtabell'));

?>
<h1>
	<?php echo $title; ?>
</h1>

<?php 

// Show some general info
$this->widget('cms.widgets.CachedCmsBlock', array('name'=>'timetable-info'));

// Loop through the dates in the timetable and render a grid for each day
$dateFormatter = Yii::app()->dateFormatter;

foreach ($dates as $date)
{
	$dateString = ucfirst($dateFormatter->format("EEEE d.M", $date->format('r')));
	
	echo CHtml::openTag('div', array('class'=>'timetable-date'));
	echo CHtml::tag('h4', array(), $dateString);

	$this->renderPartial('_timetableDate', array(
		'lan'=>$lan,
		'date'=>$date,
	));

	echo CHtml::closeTag('div');
	
	echo '<hr />';
}