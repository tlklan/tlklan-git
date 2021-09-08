<?php

/* @var $this CompetitionController */
/* @var $model Competition */

?>
<p>
	Skriv in den slutliga positionen för deltagarna i tävlingen. Lämna tomt 
	om du inte vet positionen.
</p>

<div class="position-grid">
	<?php $this->widget('TbGridView', array(
		'id'=>'position-grid',
		'type'=>'striped bordered',
		'dataProvider'=>$model->getActualCompetitorDataProvider(),
		'template'=>'{items}',
		'columns'=>array(
			array(
				'name'=>'registration_id',
				'value'=>'$data->registration->user->name',
			),
			array(
				'name'=>'position',
				'type'=>'raw',
				'value'=>'CHtml::textField("position[$data->id]", $data->position)',
				'htmlOptions'=>array('class'=>'position'),
			),
		)
	)); ?>
</div>