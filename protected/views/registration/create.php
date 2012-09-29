<?php

// Register additional styles for small-screened devices
Yii::app()->clientScript->registerCssFile(
		Yii::app()->baseUrl.'/css/small-screen.css', 
		'only screen and (max-device-width: 480px)');

$this->pageTitle = 'Anmälning till '.$currentLan->name;
$this->breadcrumbs=array(
	'Anmälning',
);

?>
<div class="registration-container row clearfix">
	<div class="registration-form span7">
		<h1>Anmälningar till <?php echo $currentLan->name; ?></h1>

		<div class="disclaimer">
			<?php $this->widget('cms.widgets.CmsBlock', array(
				'name'=>'registration_disclaimer'
			)); ?>
		</div>
		
		<?php $this->renderPartial('_form', array(
			'model'=>$model,
			'registration'=>$registration,
			'competitions'=>$competitions,
		)); ?>
	</div>
	
	<div class="registration-info small-screen-hidden span5">
		<h1 style="margin-top: 0;">Information</h1>
		<?php $this->widget('cms.widgets.CmsBlock',array('name'=>'registration_info')); ?>
		
		
	</div>
	
	<div class="statistics small-screen-hidden">
		<h3>Tävlingsstatistik</h3>

		<table class="stat_counter" cellpadding="0" cellspacing="0">
			<?php

			$competitionStats = Competition::model()->getStatisticsByLan($currentLan->id);
			foreach($competitionStats as $competition => $competitorCount) {
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
</div>
<?php

$registrations = Registration::model()->findByLan($currentLan->id);

// Render the list of registered people
$this->widget('application.widgets.registration.RegistrationListWidget', array(
	'currentLan'=>$currentLan,
	'registrations'=>$registrations,
	'competitions'=>$competitions,
));