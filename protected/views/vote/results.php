<?php

$this->pageTitle = Yii::t('vote', 'Resultat');
$this->breadcrumbs = array(
	Yii::t('vote', 'Röstningsresultat'),
);

?>
<h1><?php Yii::t('vote', 'Resultat'); ?></h1>

<?php $this->widget('cms.widgets.CmsBlock', array('name'=>'vote-result-info')); ?>

<hr />

<?php

/* @var $form TbActiveForm */
/* @var $model VoteForm */
$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
	'action'=>$this->createUrl('/vote/ajaxResults'),
));

echo $form->dropDownListRow($model, 'competition', CHtml::listData($competitions, 'id', 'full_name'));

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'ajaxSubmit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>Yii::t('vote', 'Visa resultat'),
		// AJAX options
		'url'=>Yii::app()->controller->createUrl('/vote/ajaxResults'),
		'loadingText'=>Yii::t('vote', 'Laddar...'),
		'htmlOptions'=>array('id'=>'stateful-button'),
		'ajaxOptions'=>array(
			'update'=>'#result-list',
			'beforeSend'=>'function() {
				$("#stateful-button").html("'.Yii::t('vote', 'Laddar...').'");
			}',
			'complete'=>'function(){
				$("#stateful-button").html("<i class=\"icon-ok icon-white\"></i> '.Yii::t('vote', 'Visa resultat').'");
			}',
		),
		
	)); ?>
</div>

<div id="result-list"></div>
<?php

$this->endWidget();