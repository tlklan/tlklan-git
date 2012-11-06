<?php
/* @var $this RegistrationController */
/* @var $model Registration */

$this->breadcrumbs=array(
	'Registrations'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Registration', 'url'=>array('index')),
	array('label'=>'Manage Registration', 'url'=>array('admin')),
);
?>

<h1>Create Registration</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>