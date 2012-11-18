<?php
/* @var $this RegistrationController */
/* @var $registration Registration */

$this->breadcrumbs=array(
	'Anmälningar'=>array('admin'),
	$registration->name. ' - uppdatera',
);

?>

<h1>
	Uppdatera anmälan för <i><?php echo $registration->name; ?></i> till 
	<?php echo $registration->lan->name; ?>
</h1>

<hr />

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'registration'=>$registration,
	'competitions'=>$competitions,
));