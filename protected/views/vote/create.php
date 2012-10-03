<?php

$this->pageTitle = 'Rösta';
$this->breadcrumbs = array(
	'Rösta',
);

?>
<h1>Rösta</h1>

<?php $this->widget('cms.widgets.CmsBlock', array('name'=>'vote-info')); ?>

<hr />

<?php

$this->renderPartial('_form', array(
	'registrations'=>$registrations,
	'competitions'=>$competitions,
	'model'=>$model,
));