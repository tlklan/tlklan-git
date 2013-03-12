<?php

// Script for toggling display of the list
Yii::app()->clientScript->registerScript('toggle-won-competitions-list', "
	$('#toggle-won-competitions-list').click(function() {
		$('#won-competitions-list').toggle(100);
		
		return false;
	});
", CClientScript::POS_READY);

$wonCompetitionCount = count($actualCompetitors);

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

			foreach ($actualCompetitors as $actualCompetitor) 
			{
				
				$competition = $actualCompetitor->competition->full_name;
				$lan = $actualCompetitor->competition->lan->name;
				
				?>
				<tr>
					<td><?php echo $competition; ?></td>
					<td><?php echo $lan; ?></td>
				</tr>
				<?php
			}

			?>
		</table>
	</div>
	<?php
}