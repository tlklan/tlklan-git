<table class="submission-list table table-striped table-bordered">
	<tr>
		<th><?php echo Yii::t('profile', 'Tävling'); ?>:</th>
		<th><?php echo Yii::t('profile', 'Namn'); ?>:</th>
	</tr>
	<?php

	/* @var $submissions Submission[] */
	foreach ($submissions as $submission)
	{
		$competition = $submission->competition;

		// Competition display name
		$competitionName = $competition->full_name
			.' <span class="lan-name">('.$competition->lan->name.')</span>';
		
		?>
		<tr>
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