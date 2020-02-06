<?php

/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */
/* @var $form TbActiveForm */

$form = $this->beginWidget('TbActiveForm', array(
	'type'=>'horizontal',
	'enableAjaxValidation'=>false
));

?>
<div class="control-group">
	<?php echo $form->labelEx($model, 'name', array('class'=>'control-label')); ?>
	<div class="controls">
		<?php $this->widget('bootstrap.widgets.TbTypeahead', array(
			'model'=>$model,
			'attribute'=>'name',
			'options'=>array(
				'name'=>'typeahead',
				'source'=>User::model()->getTypeaheadData(),
				'items'=>5,
				'matcher'=>"js:function(item) {
					return ~item.toLowerCase().indexOf(this.query.toLowerCase());
				}",
			),
			'htmlOptions'=>array('autocomplete'=>'off'),
		));

		if ($model->hasErrors('name'))
			echo CHtml::tag('span', array('class'=>'help-inline error'), $model->getError('name'));

		?>
	</div>
</div>
<?php

//echo $form->dropDownListRow($model, 'user_id', CHtml::listData(User::model()->findAll(), 'id', 'name'));
echo $form->textFieldRow($model, 'year');
echo $form->dropDownListRow($model, 'position', CommitteeMember::$availablePositions);

?>
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>$model->isNewRecord ? 'Skapa' : 'Uppdatera',
	)); ?>
	&nbsp;&nbsp;&nbsp;
	<?php $this->widget('TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('admin'),
	)); ?>
</div>
<?php

$this->endWidget();
