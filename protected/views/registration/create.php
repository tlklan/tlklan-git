<?php

// Register additional styles for small-screened devices
Yii::app()->clientScript->registerCssFile(
		Yii::app()->baseUrl.'/css/small-screen.css', 
		'only screen and (max-device-width: 720px)');

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
		
		<?php 
		
		// Show the registration form to logged in users only
		if (Yii::app()->user->isGuest) 
		{
			?>
			<div class="alert alert-error alert-block">
				Du måste vara inloggad för att registrera dig. Har du inte ett 
				konto är det bara att registrera sig!
			</div>
			<?php
		}
		else 
		{
			$this->renderPartial('_form', array(
				'model'=>$model,
				'registration'=>$registration,
				'competitions'=>$competitions,
			)); 
		}
		
		?>
	</div>
	
	<div class="registration-info small-screen-hidden span5">
		<h1 style="margin-top: 0;">Information</h1>
		<?php $this->widget('cms.widgets.CmsBlock',array('name'=>'registration_info')); ?>
	</div>
	
	<?php
	
	// Don't show the statistics to guests (there's not enough vertical space
	// for it)
	if (!Yii::app()->user->isGuest)
	{
		$this->renderPartial('_statistics', array(
			'currentLan'=>$currentLan,
		));
	}
	
	?>
</div>
<?php

// Render the list of registered people
$this->widget('application.widgets.registration.RegistrationListWidget', array(
	'currentLan'=>$currentLan,
));