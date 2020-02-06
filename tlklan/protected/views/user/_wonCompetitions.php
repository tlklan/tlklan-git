<?php

/* @var $wonCompetitions Competition[] */

// Script for toggling display of the list
Yii::app()->clientScript->registerScript('toggle-won-competitions-list', "
	$('#toggle-won-competitions-list').click(function() {
		$('#won-competitions-list').toggle(100);
		
		return false;
	});
", CClientScript::POS_READY);

$wonCompetitionCount = count($wonCompetitions);

echo Yii::t('user', '{wonCompetitions} st', array(
	'{wonCompetitions}'=>$wonCompetitionCount));

if ($wonCompetitionCount > 0) 
{
	?>

	<a id="toggle-won-competitions-list" class="lan-name" href="#">
		<?php echo Yii::t('user', '(visa lista)'); ?>
	</a>

	<div id="won-competitions-list" class="profile-inner-list">
		<table class="table table-striped table-bordered">
			<tr>
				<th><?php echo Yii::t('lan', 'Tävling'); ?></th>
				<th><?php echo Yii::t('lan', 'LAN'); ?></th>
			</tr>
			<?php

			foreach ($wonCompetitions as $competition) 
			{
				?>
				<tr>
					<td><?php echo $competition->full_name; ?></td>
					<td><?php echo $competition->lan->name; ?></td>
				</tr>
				<?php
			}

			?>
		</table>
	</div>
	<?php
}