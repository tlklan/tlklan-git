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
	 * @var array All registrations for this LAN
	 */
	public $registrations;

	/**
	 * @var array All competitions for this LAN
	 */
	public $competitions;

	/**
	 * @var int the number of registrations for this LAN
	 */
	private $_registrationCount;

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		$this->_registrationCount = count($this->registrations);
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
		
		?>
		<table class="table table-striped table-bordered table-condensed" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<?php
				
				// Show the 'actions' column for authenticated users
				if(!$user->isGuest) {
					?><th>&nbsp;</th><?php
				}
				
				?>
				<th>Namn:</th>
				<th>Nick:</th>
				<?php
				
				if($user->isAdmin())
					echo '<th>E-post:</th>';
				
				?>
				<th class="thick_right_border">Laptop:</th>
				<?php

				// Add one column for each competition
				foreach($this->competitions as $competition) 
				{
					?><th class="compo_header"><?php echo $competition->short_name; ?>:</th><?php
				}

				?>
				<th class="thick_left_border">Anmälan gjord:</th>
			</tr>
			<?php

			// Print each row
			foreach($this->registrations as $registration) 
			{
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
					if(!$user->isGuest) {
						echo '<td>';
						
						// Show edit link for the user's own registration and 
						// for administrators
						$isOwner = strtolower($registration->nick) === strtolower($user->nick);
						
						if($isOwner || $user->isAdmin()) 
						{
							echo CHtml::link(
								CHtml::image(
									Yii::app()->baseUrl.'/files/images/icons/edit_button.png'
								), 
								Yii::app()->controller->createUrl('registration/update', array('id'=>$registration->id))
							);
							echo '&nbsp;';
						}
						
						// Show delete link for administrators
						if($user->isAdmin()) 
						{
							echo CHtml::link(
								CHtml::image(Yii::app()->baseUrl.'/files/images/icons/delete_button.png'), 
								Yii::app()->controller->createUrl('registration/delete', array('id'=>$registration->id)),
								array('confirm'=>'Är du säker?')
							);
						}
						
						echo '</td>';
					}
					
					?>
					<td><?php echo $name; ?></td>
					<td><?php echo $nick; ?></td>
					<?php
					
					if($user->isAdmin())
					{
						echo '<td>';
						echo CHtml::mailto(CHtml::encode($registration->email), 
								$registration->email);
						echo '</td>';
					}
					
					?>
					<td class="thick_right_border center-align">
						<?php echo ($registration->hasLaptop()) ? 'x' : ''; ?>
					</td>
					<?php

					foreach($this->competitions as $competition) 
					{
						?>
						<td class="center-align">
							<?php echo (in_array($competition->id, $registeredCompetitions)) ? 'x' : ''; ?>
						</td>
						<?php
					}

					?>
					<td class="thick_left_border" style="width: 140px;">
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
		<p>Antal registrerade hittills: <b><?php echo $this->_registrationCount; ?> / <?php echo $this->currentLan->reg_limit; ?></b></p>
		<?php
		
		echo ob_get_clean();
	}
}
