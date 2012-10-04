<?php

$this->pageTitle = 'Tävlingsanmälan (under LAN)';
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

echo $form->dropDownListRow($model, 'registration', CHtml::listData($registrations, 'id', 'nick'));
echo $form->dropDownListRow($model, 'competition', CHtml::listData($competitions, 'id', 'full_name'));

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

foreach($competitions as $competition) 
{
	echo '<h2>'.$competition->full_name.'</h2>';
	
	$this->widget('bootstrap.widgets.TbGridView', array(
		'type'=>'striped bordered condensed',
		'dataProvider'=>$competition->getActualCompetitorDataProvider(),
		'template'=>"{items}",
		'emptyText'=>'Ingen har ännu anmält sig till den här tävlingen',
		'columns'=>array(
			'registration.nick',
			array(
				'class'=>'bootstrap.widgets.TbButtonColumn',
				'template'=>'{delete}',
			),
		),
	));
}