<div class="statistics small-screen-hidden">
	<h3>Tävlingsstatistik</h3>

	<table class="stat_counter" cellpadding="0" cellspacing="0">
		<?php

		$statistics = $currentLan->getCompetitionStatistics();
		foreach($statistics as $competition => $competitorCount) {
			?>
			<tr>
				<td><?php echo $competition; ?></td>
				<td><b><?php echo $competitorCount; ?></b></td>
			</tr>
			<?php
		}

		?>
	</table>
</div>