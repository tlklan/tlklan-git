<?php

/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */

$this->breadcrumbs=array(
	'Styrelsemedlemmar'=>array('admin'),
	'Skapa ny medlem',
);

?>

<h1>Skapa ny medlem</h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model));
