<?php

/* @var $user User */

// Script for toggling display of the list
Yii::app()->clientScript->registerScript('toggle-lan-list', "
	$('#toggle-lan-list').click(function() {
		$('#lan-list').toggle(100);
		
		return false;
	});
", CClientScript::POS_READY);

?>
<div class="row">
	<div class="span3">
		<?php

		// Amount of LANs and the link to the table
		echo count($user->lans).' '.Yii::t('general', 'st').' ';
		echo CHtml::link(Yii::t('user', '(visa lista)'), '#', array(
			'id'=>'toggle-lan-list',
			'class'=>'lan-name'));

		?>
	</div>
	<div class="span4">
		<?php

		// Efficiency bar
		$efficiency = $user->getLanEfficiency();

		// Determien bar color
		$barType = 'danger';

		if ($efficiency >= 30)
			$barType = 'warning';
		if ($efficiency >= 75)
			$barType = 'success';

		// Show the efficiency
		$formatter = new CNumberFormatter(Yii::app()->language);

		echo CHtml::openTag('p');
		echo '<b>'.Yii::t('user', 'Effektivitet').': </b>';
		echo $formatter->format('##', $efficiency).'%';

		// Show explanation
		$this->widget('bootstrap.widgets.TbButton', array(
			'label'=>'What',
			'type'=>'info',
			'size'=>'small',
			'icon'=>'white question-sign',
			'htmlOptions'=>array(
				'data-title'=>Yii::t('user', 'Effektivitet'), 
				'data-content'=>Yii::t('user', 'LAN-effektiviten räknas som hur många av de senaste två årens LAN man deltagit i.'), 
				'rel'=>'popover',
				'style'=>'margin-left: 20px;',
			),
		));

		echo CHtml::closeTag('p');

		$this->widget('bootstrap.widgets.TbProgress', array(
			'type'=>$barType,
			'percent'=>$efficiency,
			'striped'=>true,
			'animated'=>true,
		));

		?>
	</div>
</div>

<div class="clearfix"></div>

<div id="lan-list" class="profile-inner-list">
	<table class="table table-striped table-bordered">
		<tr>
			<th><?php echo Yii::t('lan', 'Namn'); ?></th>
			<th><?php echo Yii::t('lan', 'Startdatum'); ?></th>
			<th><?php echo Yii::t('lan', 'Plats'); ?></th>
		</tr>
		<?php

		foreach ($user->lans as $lan) 
		{
			?>
			<tr>
				<td><?php echo $lan->name; ?></td>
				<td><?php echo $lan->start_date; ?></td>
				<td><?php echo Lan::$locationList[$lan->location]; ?></td>
			</tr>
			<?php
		}

		?>
	</table>
</div>