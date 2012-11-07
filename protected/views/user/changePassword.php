<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Byt lösenord';
$this->breadcrumbs=array(
	'Din profil'=>array('profile'),
	'Byt lösenord',
);

?>
<h1>Byt lösenord</h1>

<hr />

<?php echo $this->renderPartial('_changePasswordForm', array('model'=>$model)); ?>