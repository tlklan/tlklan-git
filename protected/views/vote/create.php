<?php

$this->pageTitle = Yii::t('vote', 'Rösta');
$this->breadcrumbs = array(
	Yii::t('vote', 'Rösta'),
);

?>
<h1><?php echo Yii::t('vote', 'Rösta'); ?></h1>

<?php $this->widget('cms.widgets.CmsBlock', array('name'=>'vote-info')); ?>

<hr />

<?php

$this->renderPartial('_form', array(
	'competitions'=>$competitions,
	'model'=>$model,
));