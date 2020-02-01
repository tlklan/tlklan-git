<table class="table table-striped">
	<thead>
		<tr>
			<th>Kategori</th>
			<th>Textsträng</th>
			<th>Översättning</th>
			<th>Ny översättning</th>
		</tr>
	</thead>
	<tbody>
		<?php

		foreach( $messageSourceList as $messageSource )
		{
			$message = isset($messageSource->translations[0]) ? $messageSource->translations[0] : null;
			if($message === null && !$includeMissing)
				continue;

			?>
			<tr>
				<td style="width: 250px;"><?php echo $messageSource->category; ?></td>
				<td style="width: 200px;">
					<?php echo CHtml::encode($messageSource->message); ?>
				</td>
				<td style="width: 200px;">
					<?php echo $message !== null ? CHtml::encode($message->translation) : ''; ?>
				</td>
				<td style="width: 200px;">
					<?php

					echo CHtml::hiddenField('messageSourceId[]', $messageSource->id);
					echo CHtml::textArea('translation[]', '', array(
						'style'=>'width: 200px; height: 40px;',
						'cols'=>'',
						'rows'=>'',
					));

					?>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>