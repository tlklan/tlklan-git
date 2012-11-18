<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Ändra uppgifter';
$this->breadcrumbs=array(
	'Användare'=>array('admin'),
	'Ändra uppgifter',
);

?>
<h1>Ändra uppgifter</h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>