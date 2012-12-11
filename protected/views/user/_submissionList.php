<table class="submission-list table table-striped table-bordered">
	<tr>
		<th>Tävling:</th>
		<th>Namn:</th>
	</tr>
	<?php

	foreach ($submissions as $submission)
	{
		$competition = $submission->competition;

		// Mark the row if the user won with this particular submission
		$trClass = '';

		$winningSubmission = SubmissionVote::model()->getWinningSubmission($competition->id);

		if ($winningSubmission->user_id == $submission->user_id)
			$trClass = 'winner';
		
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