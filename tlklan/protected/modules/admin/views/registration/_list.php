<?php

$this->widget('TbGridView', array(
	'id'=>'registration-grid',
	'type'=>'striped bordered',
	'dataProvider'=>$dataProvider,
	'filter'=>isset($disableFilter) ? null : $model,
	'enableSorting'=>isset($disableSorting) ? false : true, 
	'columns'=>array(
		array(
			'name'=>'lan_id',
			'filter'=>Lan::model()->getListData(),
			'value'=>'$data->lan->name',
		),
		array(
			'name'=>'name',
			'type'=>'raw',
			// show "has not payed" icon
			'value'=>'$data->name." ".(!$data->user->hasValidPayment() ? CHtml::image(Yii::app()->baseUrl."/files/images/icons/no_can_has_pay.png") : "")',
		),
		'email',
		'nick',
		array(
			'name'=>'device',
			'type'=>'raw',
            'header'=>'Maskin',
            'filter'=>Device::getSelectableDevices(),
            'value'=>'CHtml::image(Yii::app()->baseUrl."/files/images/icons/devices/".$data->device.".png")',
			'htmlOptions'=>array('style'=>'text-align: center; padding: 6px 8px;'),
		),
		'date',
		array(
			'class'=>'TbButtonColumn',
			'buttons'=>array(
				'addPayment'=>array(
					'label'=>'Ny betalning',
					'icon'=>'money',
					'url'=>'array("payment/create", "registrationId"=>$data->id)',
					'visible'=>'!$data->user->hasValidPayment()',
				),
				// make the URLs point to the correct controller no matter where 
				// this view is rendered
				'update'=>array(
					'url'=>'array("registration/update", "id"=>$data->id)'
				),
				'delete'=>array(
					'url'=>'array("registration/delete", "id"=>$data->id)'
				),
			),
			'template'=>'{addPayment} {update} {delete}',
		),
	),
));
