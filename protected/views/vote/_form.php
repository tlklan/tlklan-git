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


?>
<div class="control-group">
	<label class="control-label" for="<?php echo $htmlOptions['id']; ?>">
		<?php echo $model->getAttributeLabel($competitionAttr); ?>
	</label>
	
	<div class="controls">
		<?php echo $form->dropDownList($model, 'competition', 
				CHtml::listData($competitions, 'id', 'name'), array(
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
		)); ?>
		
		<div id="loading-submissions" class="loading-submissions">
			<img src="<?php echo Yii::app()->baseUrl; ?>/files/images/icons/loading_icon.gif" />
		</div>
	</div>
</div>

<?php $this->renderPartial('_submissionList', array(
	'placeholder'=>'Välj tävling först',
)); ?>

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