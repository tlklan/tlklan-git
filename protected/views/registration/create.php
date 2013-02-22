<?php

/* @var $currentLan Lan */
/* @var $registration Registration */

$this->pageTitle = Yii::t('registration', 'Anmälning till {lanName}', array('{lanName}'=>$currentLan->name));
$this->breadcrumbs = array(
	Yii::t('registration', 'Anmälningar till {lanName}', array('{lanName}'=>$currentLan->name)),
);

?>
<div class="row clearfix">
	
	<?php
	
	if (!Yii::app()->user->isGuest)
	{
		$hasRegistration = Registration::model()->currentLan()->
				findByAttributes(array('user_id'=>Yii::app()->user->userId));

		if (!$hasRegistration)
		{
			$this->renderPartial('_form', array(
				'model'=>$model,
				'currentLan'=>$currentLan,
			));
		}
	}
		
	?>
	
	<div class="hidden-tablet hidden-phone span4">
		<h2><?php echo Yii::t('registration', 'Information'); ?></h2>
		
		<?php $this->widget('cms.widgets.CmsBlock',array('name'=>'registration_info')); ?>
	</div>
	
	<div class="span3">
		<h2><?php echo Yii::t('competition', 'Tävlingsstatistik'); ?></h2>

		<table class="table">
			<?php

			foreach ($currentLan->competitions as $competition) 
			{
				?>
				<tr>
					<td><?php echo $competition->short_name; ?></td>
					<td><b><?php echo $competition->competitorCount; ?></b></td>
				</tr>
				<?php
			}

			?>
		</table>
	</div>
	
	<div class="span12">
		<h2><?php echo Yii::t('registration', 'Anmälningar'); ?></h2>
		
		<hr />
	</div>
</div>
<?php

// Render the list of registered people
$this->widget('RegistrationList', array('currentLan'=>$currentLan));