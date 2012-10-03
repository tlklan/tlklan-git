<?php

/* @var $form TbActiveForm */
/* @var $model VoteForm */
$form = $this->beginWidget('TbActiveForm', array(
	'id'=>'vote-form',
	'type'=>'horizontal',
));

echo $form->dropDownListRow($model, 'voter', 
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

if ($model->competition)
{
	$competition = Competition::model()->findByPk($_POST['VoteForm']['competition']);
	$submissions = $competition->submissions;

	echo CHtml::openTag('div', array('id'=>'submission-list'));
	$this->renderPartial('_submissionList', array(
		'model'=>$model,
		'data'=>CHtml::listData($submissions, 'id', 'name')
	));
	echo CHtml::closeTag('div');
}
else
{
	$this->renderPartial('_placeholder', array(
		'placeholder'=>'Välj tävling först',
	));
}

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