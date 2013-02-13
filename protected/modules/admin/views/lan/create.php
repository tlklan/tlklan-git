<?php

$this->pageTitle = 'Skapa ett nytt LAN';
$this->breadcrumbs=array(
	'LAN'=>array('admin'),
	'Skapa nytt',
);

?>
<h1>Skapa ett nytt LAN</h1>

<hr />
<?php echo $this->renderPartial('_form', array('model'=>$model));