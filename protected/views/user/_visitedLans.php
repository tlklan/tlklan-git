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

<?php echo count($user->lans); ?> st 
<a id="toggle-lan-list" class="lan-name" href="#">(visa lista)</a>

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