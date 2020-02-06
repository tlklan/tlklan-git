<?php

/* @var $this RegistrationController */
/* @var $model Registration */

$this->pageTitle = 'Uppdatera anmälan för '.CHtml::encode($model->name);
$this->breadcrumbs=array(
	'Anmälningar'=>array('admin'),
	$model->name. ' - uppdatera',
);

?>

<h1>
	Uppdatera anmälan för <i><?php echo $model->name; ?></i> till 
	<?php echo $model->lan->name; ?>
</h1>

<hr />

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'competitions'=>$competitions,
));