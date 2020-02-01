<?php

/* @var $model Registration */
$lan = $model->lan;

$this->pageTitle = Yii::t('registration', 'Uppdatera anmälan');
$this->breadcrumbs = array(Yii::t('registration', 'Uppdatera anmälan'));

?>
<div class="row">
	<?php $this->renderPartial('_form', array(
		'model'=>$model,
		'currentLan'=>$lan,
	)); ?>
</div>