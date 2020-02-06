<?php

/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */

$this->breadcrumbs=array(
	'Styrelsemedlemmar'=>array('admin'),
	'Uppdatera',
);

?>

<h1>Uppdatera styrelsemedlem</h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model));
