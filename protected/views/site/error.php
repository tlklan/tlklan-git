<?php
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('general', 'Fel');
$this->breadcrumbs=array(
	Yii::t('general', 'Fel'),
);
?>

<h2><?php echo Yii::t('general', 'Fel'); ?> <?php echo $code; ?></h2>

<div class="error">
	<?php echo CHtml::encode($message); ?>
</div>