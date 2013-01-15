<?php

$this->pageTitle = Yii::t('competition', 'Tävlingsanmälan (under LAN)');
$this->breadcrumbs=array(
	'Tävlingsanmälan (under LAN)',
);

?>
<h1>Tävlingsanmälan (under LAN)</h1>

<?php $this->widget('cms.widgets.CmsBlock',array('name'=>'compo-reg-info')); ?>

<hr />
<?php

/* @var $form TbActiveForm */
/* @var $model CompetitionRegistrationForm */
$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
));

echo $form->dropDownListRow($model, 'competition', CHtml::listData($competitions, 'id', 'nameAndDeadline'), array('prompt'=>''));

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>'Anmäl dig'
	)); ?>
</div>
<?php $this->endWidget(); ?>
<hr />

<h1>Anmälningar</h1>
<?php

// Show all competitions, not just those whose deadline haven't passed
foreach($allCompetitions as $competition) 
{
	$dataProvider = $competition->getActualCompetitorDataProvider();
	
	echo '<h2>'.$competition->full_name.'</h2>';
	echo CHtml::openTag('p', array('class'=>'competitor-count'));
	echo 'Antal anmälda: <b>'.$dataProvider->totalItemCount.'</b>';
	echo CHtml::closeTag('p');
	
	// Only show the button column for logged in users
	$columns = array('registration.nick');
	if(Yii::app()->user->isAdmin())
	{
		$columns[] = array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{delete}',
		);
	}

	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped bordered condensed',
		'dataProvider'=>$dataProvider,
		'template'=>"{items}",
		'emptyText'=>'Ingen har ännu anmält sig till den här tävlingen',
		'columns'=>$columns
	));
}