<div class="lan-competition-grid">
	<?php 

	$this->beginWidget('TbActiveForm', array(
		'enableAjaxValidation'=>true,
	));

	$this->widget('TbGridView', array(
		'id'=>'competition-grid',
		'type'=>'striped bordered',
		'dataProvider'=>$competitionDataProvider,
		'template'=>'{items}',
		'columns'=>array(
			'full_name',
			'competitorCount',
			array(
				'name'=>'display_order',
				'type'=>'raw',
				'value'=>'CHtml::textField("display_order[$data->id]", $data->display_order)',
				'htmlOptions'=>array('class'=>'display-order'),
			),
			array(
				'name'=>'votable',
				'type'=>'raw',
				'value'=>'($data->votable=="1")?(\'<i class="icon-ok"></i>\'):""',
				'htmlOptions'=>array('style'=>'text-align: center;')
			),
			array(
				'name'=>'signupable',
				'type'=>'raw',
				'value'=>'($data->signupable=="1")?(\'<i class="icon-ok"></i>\'):""',
				'htmlOptions'=>array('style'=>'text-align: center;')
			),
			'deadline',
			array(
				'class'=>'TbButtonColumn',
				'htmlOptions'=>array('style'=>'width: 50px;'),
				'template'=>'{update} {delete}',

				// the URLs are pointing to LanController by default
				'updateButtonUrl'=>'Yii::app()->controller->createUrl("competition/update", array("id"=>$data->id))',
				'deleteButtonUrl'=>'Yii::app()->controller->createUrl("competition/delete", array("id"=>$data->id))',
			)
		)
	));

	?>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'ajaxSubmit',
			'type'=>'primary',
			'icon'=>'ok white',
			'label'=>'Uppdatera ordning',
			'url'=>$this->createUrl('competition/ajaxUpdate', array('action'=>'updateDisplayOrder')),
			'ajaxOptions'=>array(
				'success'=>'updateGrid',
			),
		)); ?>
	</div>

	<?php $this->endWidget(); ?>
</div>