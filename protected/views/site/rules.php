<?php

/* @var $this SiteController */
/* @var $competitions Competition[] */

?>
<h1><?php echo Yii::t('competition', 'Regler'); ?></h1>

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