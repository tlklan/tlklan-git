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
		'label'=>Yii::t('competition', 'Anmäl dig'),
	)); ?>
</div>
<?php $this->endWidget(); ?>
<hr />

<h1><?php echo Yii::t('competition', 'Deltagare'); ?></h1>

<?php 

$this->widget('cms.widgets.CmsBlock', array('name'=>'compo-reg-list-info'));

// Display one tab for each competition
$tabs = array();

foreach ($allCompetitions as $competition)
{
	/* @var $competition Competition */
	/* @var $dataProvider CActiveDataProvider */
	$dataProvider = $competition->getActualCompetitorDataProvider();

	$label = $competition->full_name.' ('.$dataProvider->totalItemCount.')';
	$content = $this->renderPartial('_participantsTab', array(
		'dataProvider'=>$dataProvider,
		'competition'=>$competition), true);

	$active = empty($tabs); // first item will be active
	$tabs[] = array('label'=>$label, 'content'=>$content, 'active'=>$active);
}

// Render the tabs
$this->widget('TbTabs', array(
	'type'=>'tabs',
	'tabs'=>$tabs,
));