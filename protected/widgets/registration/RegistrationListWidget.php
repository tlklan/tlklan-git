<?php

/**
 * Renders the list of registered people in registration/index
 *
 * @author Sam
 */
class RegistrationListWidget extends CWidget 
{
	
	/**
	 * @var Lan the current LAN
	 */
	public $currentLan;

	/**
	 * @var int the number of registrations for this LAN
	 */
	private $_registrationCount;

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		$this->_registrationCount = count($this->currentLan->registrations);
	}

	/**
	 * Runs the widget. The rendering is done here.
	 */
	public function run() 
	{
		ob_start();
		
		?>
		<div class="registration-list">
			<?php
			
			$this->renderRegistrationCount();
			if ($this->_registrationCount > 0)
				$this->renderList();
			
			?>
		</div>
		<?php
		
		echo ob_get_clean();
	}

	/**
	 * Renders the actual list
	 */
	private function renderList() 
	{
		ob_start();
		
		// Get the user object
		$user = Yii::app()->user;
		
		// Check if the user has a registration. We need to know this so we 
		// know which columns to show
		$hasRegistration = (Registration::model()->currentLan()
				->find('user_id = :user_id', 
				array(':user_id'=>$user->getUserId())) !== null);
		
		?>
		<table class="table table-striped table-bordered table-condensed" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<?php
				
				// Show the 'actions' column for authenticated users (although 
				// not for administrators)
				if(!$user->isGuest && $hasRegistration) {
					?><th>&nbsp;</th><?php
				}
				
				?>
				<th><?php echo Yii::t('registration', 'Namn'); ?>:</th>
				<th><?php echo Yii::t('registration', 'Nick'); ?>:</th>
				<th class="thick_right_border small-screen-hidden">Laptop:</th>
				<?php

				// Add one column for each competition
				foreach($this->currentLan->competitions as $competition) 
				{
					?><th class="compo_header small-screen-hidden"><?php echo $competition->short_name; ?>:</th><?php
				}

				?>
				<th class="thick_left_border small-screen-hidden">
					<?php echo Yii::t('registration', 'Anmälan gjord'); ?>
				</th>
			</tr>
			<?php

			// Print each row
			foreach($this->currentLan->registrations as $registration) 
			{
				/* @var $registration Registration */
				
				$name = CHtml::encode($registration->name);
				$nick = CHtml::encode($registration->nick);
				$registeredCompetitions = array();
				
				// Get a list of the IDs of the competitions the current user
				// is registered to
				foreach($registration->competitions as $competition)
					$registeredCompetitions[] = $competition->competition_id;
				
				?>
				<tr>
					<?php
					
					// Show an edit/delete links
					if(!$user->isGuest && $hasRegistration) {
						echo '<td>';
						
						if(strtolower($registration->user_id) === strtolower($user->getUserId())) 
						{
							echo CHtml::link(
								'<i class="icon icon-pencil"></i>',
								Yii::app()->controller->createUrl('registration/update', array('id'=>$registration->id))
							);
							
							echo '&nbsp;';
							
							echo CHtml::link(
								'<i class="icon icon-trash"></i>',
								Yii::app()->controller->createUrl('registration/delete', array('id'=>$registration->id)),
								array('confirm'=>Yii::t('general', 'Är du säker?'))
							);
						}
						
						echo '</td>';
					}
					
					?>
					<td><?php echo $name; ?></td>
					<td class="nick">
						<?php 
						
						// Show link to user profile
						echo CHtml::link($nick, Yii::app()->controller
								->createUrl('user/profile', 
								array('id'=>$registration->user_id)));
						
						// Show badge for first timers
						if ($registration->isFirstTimer())
						{
							echo CHtml::image(Yii::app()->baseUrl.
									'/files/images/icons/new_icon_small.png', 
									'Possible first timer!');
						}
						
						// Show warning icon for those who haven't payed
						if (!$registration->user->hasValidPayment())
						{
							echo CHtml::image(Yii::app()->baseUrl.
									'/files/images/icons/no_can_has_pay.png',
									'Har inte betalat för det här LANet');
						}
						
						?>
					</td>
					<td class="thick_right_border center-align small-screen-hidden">
						<?php echo ($registration->hasLaptop()) ? 'x' : ''; ?>
					</td>
					<?php

					foreach($this->currentLan->competitions as $competition) 
					{
						?>
						<td class="small-screen-hidden center-align">
							<?php echo (in_array($competition->id, $registeredCompetitions)) ? 'x' : ''; ?>
						</td>
						<?php
					}

					?>
					<td class="thick_left_border small-screen-hidden" style="width: 140px;">
						<?php echo $registration->date; ?>
					</td>
				</tr>
				<?php
			}
			
			?>
		</table>
		<?php
		
		echo ob_get_clean();
	}
	
	/**
	 * Prints the amount of registered people
	 */
	private function renderRegistrationCount() 
	{
		ob_start();
		
		?>
		<p>
			<?php echo Yii::t('registration', 'Antal registrerade hittills'); ?>: 
			<b><?php echo $this->_registrationCount; ?> / 
			<?php echo $this->currentLan->reg_limit; ?></b>
			
			<img style="margin-left: 12px;" src="<?php echo Yii::app()->baseUrl; ?>/files/images/icons/new_icon_small.png" alt="Har ej deltagit förr" />
			 = <?php echo Yii::t('registration', 'har ej deltagit förut'); ?>, 
			 <img style="margin-left: 12px;" src="<?php echo Yii::app()->baseUrl; ?>/files/images/icons/no_can_has_pay.png" alt="Har ej betalt" />
			 = <?php echo Yii::t('registration', 'har ej giltig betalning'); ?>
		</p>
		<?php
		
		echo ob_get_clean();
	}
}
