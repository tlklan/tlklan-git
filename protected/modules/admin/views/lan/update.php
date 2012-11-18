<?php

$this->pageTitle = 'Uppdatera '.CHtml::encode($model->name);
$this->breadcrumbs=array(
	'LAN'=>array('admin'),
	'Uppdatera '.CHtml::encode($model->name),
);

?>

<h1>Uppdatera <?php echo CHtml::encode($model->name); ?></h1>

<hr />
<?php echo $this->renderPartial('_form',array('model'=>$model));