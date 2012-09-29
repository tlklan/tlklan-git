<?php

$this->pageTitle = 'Ny submission - TLK LAN';
$this->breadcrumbs=array(
	'Submissions'=>array('index'),
	'Create',
);

?>
<h1>Ny submission</h1>
<p>
	Fyll i fälten och klicka på "Lämna in". Kontakta LAN-crew om din submission 
	är större än <b>64 MiB</b>!
</p>

<?php 

// Display the upload form
echo $this->renderPartial('_form', array(
	'model'=>$model,
	'competitions'=>$competitions,
	'registrations'=>$registrations,
)); 

?>
<p>
	<b>OBS!</b> Genom att ladda upp dina filer här går du med på att de publiceras på 
	<?php echo CHtml::link('arkivsidan', $this->createUrl('/submission/archive')); ?>. 
	Om du inte vill ha din fil tillgänglig för nedladdning bör du 
	kontakta <?php echo CHtml::mailto('lanklubben@tlk.fi', 'lanklubben@tlk.fi'); ?>.
</p>