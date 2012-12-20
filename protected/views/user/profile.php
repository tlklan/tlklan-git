<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = $model->name;
$this->breadcrumbs=array(
	$model->name,
);

?>

<div class="user-profile">
	
	<fieldset>
		<legend>Användaruppgifter</legend>

		<div class="row">
			<div class="span2">
				<div class="picture">
					<?php echo CHtml::image($model->getProfileImageUrl()); ?>
				</div>
			</div>

			<div class="span10">
				<?php 
				
				// Get the name of the first LAN the user registered to
				$registration = Registration::model()->getFirstRegistration($model->id);
				
				// Newly registered users will not have a first LAN
				if ($registration !== null)
					$lanName = $registration->lan->name;
				
				$this->widget('TbDetailView', array(
					'type'=>'striped',
					'data'=>$model,
					'attributes'=>array(
						'name',
						'email',
						'username',
						'nick',
						array(
							'name'=>'has_werket_login',
							'type'=>'raw',
							'label'=>'Har konto på werket.tlk.fi',
							'value'=>$model->hasShellAccount() ? '<i class="icon-ok"></i>' : '',
						),
						array(
							'name'=>'date_added',
							'value'=>isset($lanName) ? $model->date_added.' <span class="lan-name">('.$lanName.')</span>' : $model->date_added,
							'type'=>'raw',
						),
					),
				)); ?>
			</div>
		</div>
	</fieldset>
	
	<div class="row">
		<div class="span6">
			<fieldset>
				<legend>Utmärkelser</legend>

				<?php

				$badges = $model->getBadges();
				$iconBaseUrl = Yii::app()->baseUrl.'/files/images/icons/badges/';

				if (count($badges) > 0)
				{
					foreach ($badges as $badge)
					{
						echo CHtml::openTag('div', array('class'=>'user-badge clearfix'));
						echo CHtml::image($iconBaseUrl.$badge->getIcon(), 'Badge');
						echo '<p>'.$badge->getDescription().'</p>';
						echo CHtml::closeTag('div');
					}
				}
				else
					echo '<p>Du har inga utmärkelser för tillfället</p>';

				?>
			</fieldset>
		</div>
		
		<div class="span6">
			<fieldset>
				<legend>Submissions</legend>

				<?php

				$submissions = $model->submissions;

				if (count($submissions) > 0)
				{
					$this->renderPartial('_submissionList', array(
						'submissions'=>$submissions,
					));
				}
				else
					echo '<p>Användaren har tills vidare inte submittat något</p>';

				?>
			</fieldset>
		</div>
	</div>
	
	<?php
	
	// Don't show action buttons unless the user "owns" the profile
	if($model->id == Yii::app()->user->getUserId()) 
	{
		?>
		<div class="form-actions">
			<?php

			$this->widget('bootstrap.widgets.TbButton', array(
				'type'=>'primary',
				'buttonType'=>'link',
				'icon'=>'edit white',
				'label'=>'Ändra uppgifter',
				'url'=>$this->createUrl('user/update'),
			));

			echo '&nbsp;&nbsp;&nbsp;';

			// Shell users must change their password from the shell
			if (!$model->hasShellAccount())
			{
				$this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'link',
					'icon'=>'edit',
					'label'=>'Byt lösenord',
					'url'=>$this->createUrl('user/changePassword'),
				));
			}

			?>
		</div>
		<?php 
	} 
	
	?>
</div>