<?php

/* @var $model Competition */
$dataProvider = $model->search();
$dataProvider->sort = array(
	'defaultOrder'=>'lan_id DESC, display_order ASC',
);

?>
<div class="lan-competition-grid">
	<?php $this->widget('TbGridView', array(
		'id'=>'competition-grid',
		'dataProvider'=>$dataProvider,
		'filter'=>$model,
		'template'=>'{summary} {items} {pager}',
		'columns'=>array(
			array(
				'name'=>'lan_id',
				'type'=>'raw',
				'filter'=>CHtml::listData(Lan::model()->findAll(), 'id', 'name'),
				'value'=>'$data->lanName',
			),
			'full_name',
			'display_order',
			array(
				'name'=>'votable',
				'type'=>'raw',
				'filter'=>array('1'=>'Ja','0'=>'Nej'),
				'value'=>'($data->votable=="1")?(\'<i class="icon-ok"></i>\'):""',
			),
			array(
				'name'=>'signupable',
				'type'=>'raw',
				'filter'=>array('1'=>'Ja','0'=>'Nej'),
				'value'=>'($data->signupable=="1")?(\'<i class="icon-ok"></i>\'):""',
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
	)); ?>
</div>