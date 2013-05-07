<?php

/* @var $model Timetable */
/* @var $lan Lan */
/* @var $date DateTime */
$this->beginWidget('TbActiveForm');

$this->widget('TbGridView',array(
	'type'=>'bordered',
	'dataProvider'=>$model->search($lan->id, $date),
	'template'=>'{items}',
	'columns'=>array(
		array(
			'class'=>'DateInputColumn',
			'name'=>'start_time',
			'dateWidth'=>null,
			'timeWidth'=>'short',
			'htmlOptions'=>array('class'=>'span1')
		),
		array(
			'class'=>'DateInputColumn',
			'name'=>'end_time',
			'dateWidth'=>null,
			'timeWidth'=>'short',
			'htmlOptions'=>array('class'=>'span1')
		),
		array(
			'class'=>'TextInputColumn',
			'name'=>'name',
			'htmlOptions'=>array('class'=>'span4')
		),
		array(
			'class'=>'DropdownInputColumn',
			'name'=>'type',
			'listData'=>Timetable::$types,
		),
		array(
			'class'=>'TbButtonColumn',
			'template'=>'{delete}',
		)
	),
)); 

?>
	
<div class="form-actions">
	<?php $this->widget('TbButton', array(
		'buttonType'=>'ajaxButton',
		'label'=>'Lägg till rad',
	)); ?>&nbsp;&nbsp;&nbsp;
	<?php $this->widget('TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'Spara',
	)); ?>
</div>

<?php $this->endWidget(); ?>