<?php

$this->pageTitle = Yii::t('competition', 'Tävlingsanmälan (under LAN)');
$this->breadcrumbs=array(
	Yii::t('competition', 'Tävlingsanmälan (under LAN)'),
);

?>
<h1><?php echo Yii::t('competition', 'Tävlingsanmälan (under LAN)'); ?></h1>

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
		'label'=>Yii::t('general', 'Anmäl dig'),
	)); ?>
</div>
<?php $this->endWidget(); ?>
<hr />

<h1><?php echo Yii::t('competition', 'Anmälningar'); ?></h1>
<?php

// TODO: Display each competition in a tab instead of a long list
// Show all competitions, not just those whose deadline haven't passed
foreach($allCompetitions as $competition) 
{
	/* @var $dataProvider CActiveDataProvider */
	$dataProvider = $competition->getActualCompetitorDataProvider();
	
	echo '<h2>'.$competition->full_name.'</h2>';
	echo CHtml::openTag('p', array('class'=>'competitor-count'));
	echo Yii::t('competition', 'Antal anmälda: <b>{count}</b>', array(
		'{count}'=>$dataProvider->totalItemCount,
	));
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
		'emptyText'=>Yii::t('competition', 'Ingen har ännu anmält sig till den här tävlingen'),
		'columns'=>$columns
	));
}