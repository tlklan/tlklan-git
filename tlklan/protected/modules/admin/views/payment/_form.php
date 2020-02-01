<?php 

/* @var $model Payment */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'type'=>'horizontal',
	'id'=>'payment-form',
	'enableAjaxValidation'=>false));

?>
<div class="control-group">
	<?php echo $form->labelEx($model, 'user_id', array('class'=>'control-label')); ?>
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
		
		if ($model->hasErrors('user_id'))
			echo CHtml::tag('span', array('class'=>'help-inline error'), $model->getError('user_id'));
		
		?>
	</div>
</div>
<?php

echo $form->dropdownListRow($model, 'lan_id', $lanListData);
echo $form->dropdownListRow($model, 'season_id', Season::model()->getDropdownListOptions(), array('class'=>'span4', 'prompt'=>''));
echo $form->dropdownListRow($model, 'type', $model->getValidTypes());

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'icon'=>'pencil white',
		'type'=>'primary',
		'label'=>$model->isNewRecord ? 'Skapa' : 'Uppdatera',
	)); ?>
	&nbsp;&nbsp;
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'link',
		'icon'=>'remove',
		'label'=>'Avbryt',
		'url'=>$this->createUrl('payment/admin'),
	)); ?>
</div>

<?php $this->endWidget();