<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Ändra uppgifter för '.CHtml::encode($model->name);
$this->breadcrumbs=array(
	'Användare'=>array('admin'),
	'Ändra uppgifter',
);

?>
<h1>Ändra uppgifter för <i><?php echo $model->name; ?></i></h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>