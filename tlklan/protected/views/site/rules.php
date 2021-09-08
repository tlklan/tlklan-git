<?php

/* @var $this SiteController */
/* @var $competitions Competition[] */
$this->pageTitle = Yii::t('competition', 'Regler');
$this->breadcrumbs = array(Yii::t('competition', 'Regler'));

?>
<h1><?php echo Yii::t('competition', 'Regler'); ?></h1>

<?php

if (Yii::app()->user->isAdmin()) 
{ 
	?>
	<div class="alert alert-block alert-info">
		De allmänna reglerna ändras genom att trycka på Update-knappen. 
		Tävlingsspecifika regler ändras från backenden (Tävlingar -> Hantera -> 
		Uppdatera).
	</div>
	<?php
}

?>

<div class="row">
	<div class="span9">
		<?php $this->widget('cms.widgets.CachedCmsBlock', array(
			'name'=>'global-rules')); ?>
	</div>
	
	<div class="span3">
		<img src="<?php echo Yii::app()->baseUrl; ?>/files/images/random/rules.png" alt="" />
	</div>
</div>
<?php

foreach ($competitions as $competition)
{
	$this->renderPartial('_competitionRules', array(
		'competition'=>$competition,
	));
}