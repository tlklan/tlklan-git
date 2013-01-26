<table class="submission-list table table-striped table-bordered">
	<tr>
		<th><?php echo Yii::t('profile', 'Tävling'); ?>:</th>
		<th><?php echo Yii::t('profile', 'Namn'); ?>:</th>
	</tr>
	<?php

	foreach ($submissions as $submission)
	{
		$competition = $submission->competition;

		// Mark the row if the user won with this particular submission
		$trClass = '';
		if (strtotime($competition->deadline) < time())
			$trClass = $submission->isWinner() ? 'winning-submission' : '';

		// Competition display name
		$competitionName = $competition->full_name
			.' <span class="lan-name">('.$competition->lan->name.')</span>';
		
		?>
		<tr class="<?php echo $trClass; ?>">
			<td><?php echo $competitionName; ?></td>
			<td>
				<?php 

				echo CHtml::link($submission->name, 
					$this->createUrl('submission/archive').'#'.$submission->id); 

				?>
			</td>
		</tr>
		<?php
	}

	?>
</table>