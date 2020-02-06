<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = Yii::t('user', 'Byt lösenord');
$this->breadcrumbs=array(
	Yii::t('user', 'Din profil')=>array('profile'),
	Yii::t('user', 'Byt lösenord'),
);

?>
<h1>Byt lösenord</h1>

<hr />

<?php echo $this->renderPartial('_changePasswordForm', array('model'=>$model)); ?>