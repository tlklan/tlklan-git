<?php

$this->pageTitle = 'Resultat';
$this->breadcrumbs = array(
	'Röstningsresultat',
);

?>
<h1>Resultat</h1>

<?php $this->widget('cms.widgets.CmsBlock', array('name'=>'vote-result-info')); ?>

<hr />

<?php

/* @var $form TbActiveForm */
/* @var $model VoteForm */
$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
	'action'=>$this->createUrl('/vote/ajaxResults'),
));

echo $form->dropDownListRow($model, 'competition', CHtml::listData($competitions, 'id', 'name'));

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'ajaxSubmit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>'Visa resultat',
		// AJAX options
		'url'=>Yii::app()->controller->createUrl('/vote/ajaxResults'),
		'loadingText'=>'Laddar...',
		'htmlOptions'=>array('id'=>'stateful-button'),
		'ajaxOptions'=>array(
			'update'=>'#result-list',
			'beforeSend'=>'function() {
				$("#stateful-button").html("Laddar ...");
			}',
			'complete'=>'function(){
				$("#stateful-button").html("<i class=\"icon-ok icon-white\"></i> Visa resultat");
			}',
		),
		
	)); ?>
</div>

<div id="result-list"></div>
<?php

$this->endWidget();