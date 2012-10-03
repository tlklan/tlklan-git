<?php

/* @var $form TbActiveForm */
/* @var $model VoteForm */
$form = $this->beginWidget('TbActiveForm', array(
	'id'=>'vote-form',
	'type'=>'horizontal',
));

echo $form->dropDownListRow($model, 'nick', 
		CHtml::listData($registrations, 'id', 'nick'), array('empty'=>''));

// Get the name and ID for the next form element
$competitionAttr = 'competition';
$htmlOptions = array();
CHtml::resolveNameID($model, $competitionAttr, $htmlOptions);

echo $form->dropDownListRow($model, 'competition', CHtml::listData($competitions, 'id', 'name'), array(
	'empty'=>'',
	'ajax'=>array(
		'type'=>'POST',
		'url'=>Yii::app()->controller->createUrl('/vote/ajaxSubmissions'),
		'update'=>'#submission-list',
		'beforeSend'=>'function() {
			$("#loading-submissions").addClass("visible");
		}',
		'complete'=>'function(){
			$("#loading-submissions").removeClass("visible");
		}',
	),
));

$this->renderPartial('_submissionList', array(
	'placeholder'=>'Välj tävling först',
)); 

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'thumbs-up white',
		'label'=>'Rösta'
	)); ?>
</div>
<?php

$this->endWidget();