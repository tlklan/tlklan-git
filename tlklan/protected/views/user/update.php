<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = Yii::t('user', 'Ändra uppgifter');
$this->breadcrumbs=array(
	Yii::t('user', 'Din profil')=>array('profile'),
	Yii::t('user', 'Ändra uppgifter'),
);

?>
<h1><?php echo Yii::t('user', 'Ändra uppgifter'); ?></h1>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>