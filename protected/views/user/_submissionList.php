<table class="table table-striped table-bordered">
	<tr>
		<th>Tävling:</th>
		<th>Namn:</th>
	</tr>
	<?php

	foreach ($submissions as $submission)
	{
		?>
		<tr>
			<td><?php echo $submission->competition->full_name; ?></td>
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