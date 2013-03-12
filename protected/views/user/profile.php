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
		<legend><?php echo Yii::t('user', 'Användaruppgifter'); ?></legend>

		<div class="row">
			<div class="span3">
				<div class="picture">
					<?php echo CHtml::image($model->getProfileImageUrl()); ?>
				</div>
			</div>

			<div class="span9">
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
							'label'=>Yii::t('user', 'Har konto på werket.tlk.fi'),
							'value'=>$model->hasShellAccount() ? '<i class="icon-ok"></i>' : '',
						),
						array(
							'name'=>'date_added',
							'value'=>isset($lanName) ? $model->date_added.' <span class="lan-name">('.$lanName.')</span>' : $model->date_added,
							'type'=>'raw',
						),
						array(
							'type'=>'raw',
							'label'=>Yii::t('user', 'Besökta LAN'),
							'value'=>$this->renderPartial('_visitedLans', array(
								'user'=>$model,
							), true),
						),
						array(
							'type'=>'raw',
							'label'=>Yii::t('user', 'Vunna tävlingar'),
							'value'=>$this->renderPartial('_wonCompetitions', array(
								'actualCompetitors'=>$actualCompetitors,
							), true),
						)
					),
				)); ?>
			</div>
		</div>
	</fieldset>
	
	<div class="row">
		<div class="span6">
			<fieldset>
				<legend><?php echo Yii::t('profile', 'Utmärkelser'); ?></legend>

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
				{
					echo CHtml::openTag('p');
					echo Yii::t('user', 'Du har inga utmärkelser för tillfället');
					echo CHtml::closeTag('p');
				}
					

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
				{
					echo CHtml::openTag('p');
					echo Yii::t('user', 'Användaren har tills vidare inte submittat något');
					echo CHtml::closeTag('p');
				}

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

			$this->widget('TbButton', array(
				'type'=>'primary',
				'buttonType'=>'link',
				'icon'=>'edit white',
				'label'=>Yii::t('user', 'Ändra uppgifter'),
				'url'=>$this->createUrl('user/update'),
			));

			echo '&nbsp;&nbsp;&nbsp;';

			// Shell users must change their password from the shell
			if (!$model->hasShellAccount())
			{
				$this->widget('TbButton', array(
					'buttonType'=>'link',
					'icon'=>'edit',
					'label'=>Yii::t('user', 'Byt lösenord'),
					'url'=>$this->createUrl('user/changePassword'),
				));
			}

			?>
		</div>
		<?php 
	} 
	
	?>
</div>