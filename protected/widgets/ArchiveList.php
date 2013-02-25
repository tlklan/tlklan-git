<?php

/**
 * Displays a set of submissions for a specific LAN
 *
 * @author Sam
 */
class ArchiveList extends CWidget {
	/**
	 * @var LAN the LAN associated with this widget
	 */
	public $lan;

	/**
	 * Displays a table with all submissions
	 */
	public function run() {
		ob_start();
		
		?>
		<div class="archive-container">
			<div class="archive-heading">
				<h2><?php echo $this->lan->name; ?></h2>
				<div class="back-link">
					<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl.'/files/images/icons/up_button.png'), '#'); ?>
				</div>
			</div>
			
			<table class="table table-striped archive-table" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<th><?php echo Yii::t('submission', 'Tävling'); ?>:</th>
					<th><?php echo Yii::t('submission', 'Namn'); ?>:</th>
					<th><?php echo Yii::t('submission', 'Skapare'); ?>:</th>
					<th><?php echo Yii::t('submission', 'Storlek'); ?>:</th>
					<th><?php echo Yii::t('submission', 'Kommentarer'); ?>:</th>
				</tr>
				<?php
				
				$totalSubmissionCount = 0;
				
				// Store some variables so we don't have to fetch them inside 
				// the loop
				$isGuest = Yii::app()->user->isGuest;
				$isAdmin = Yii::app()->user->isAdmin();

				if (!$isGuest)
					$userId = Yii::app()->user->getUserId();

				$baseUrl = Yii::app()->baseUrl;
				
				// Start looping through each competition
				foreach($this->lan->competitions as $competition) {
					$submissions = $competition->submissions;
					
					// Skip competitions with no submissions
					if(count($submissions) == 0)
						continue;
					
					// Put all submissions scores in an array so we can check
					// which is the winning one in the coming loop
					$submissionVotes = array();
					foreach($submissions as $submission) {
						$submissionVotes[] = $submission->voteCount;
					}
					
					$rowClasses = array('submission-separator');
					
					// Start looping through each submission
					foreach($submissions as $k => $submission) {
						// Only show the competition name once
						$competitionName = ($k == 0) ? $competition->full_name : '';
						
						// Mark disqualified submissions
						if($submission->disqualified == true)
							$rowClasses[] = 'disqualified';
						
						// Mark the winning submission(s) row, but only if there 
						// have actually been a vote. We also don't want to 
						// show the winner until the competitions deadline is 
						// over. We also don't want to mark the row as winning 
						// if it has been disqualified
						
						// Has the deadline passed?
						if (strtotime($competition->deadline) < time())
						{
							// Is it disqualified?
							if (!in_array('disqualified', $rowClasses))
							{
								// Is it a winner+
								if (count($submissions) == 1 || max($submissionVotes) > 0 
										&& $submission->voteCount == max($submissionVotes))
								{
									$rowClasses[] = 'winning-submission';
								}
							}
						}
												
						?>
						<tr class="<?php echo implode(' ', $rowClasses); ?>">
							<td><?php echo $competitionName; ?></td>
							<td>
								<?php 
								
								$submissionName = CHtml::encode($submission->name);
								
								// Show some buttons for logged in users
								if(!$isGuest) {
									// download link
									echo CHtml::link(
										CHtml::image($baseUrl.'/files/images/icons/save_icon_small.png'), 
										$this->controller->createUrl('/submission/get', array('id'=>$submission->id))
									);
									
									// Administrators can update/delete all submissions, 
									// others can only delete their own
									if($isAdmin || $submission->user_id == $userId) {
										// update link
										echo CHtml::link(
											CHtml::image($baseUrl.'/files/images/icons/edit_button.png'), 
											$this->controller->createUrl('/submission/update', array('id'=>$submission->id))
										);
										
										// delete link
										echo CHtml::link(
											CHtml::image($baseUrl.'/files/images/icons/delete_button.png'), 
											$this->controller->createUrl('/submission/delete', array('id'=>$submission->id)), 
											array(
												'confirm'=>Yii::t('submission', "Är du säker?\n\nEntryn kommer endast att ta bort från databasen, inte får hårdskivan."),
											)
										);
									}
									
									echo CHtml::link($submissionName, $this->controller->createUrl('/submission/get', array('id'=>$submission->id)), array('name'=>$submission->id)); 
								}
								else
									echo $submissionName;
								
								?>
							</td>
							<td>
								<?php 

								// Some older submissions doesn't have a submitter
								if ($submission->submitter !== null) 
								{
									echo CHtml::link($submission->submitter->nick, 
										$this->controller
											->createUrl('user/profile', 
										array('id'=>$submission->user_id)));
								}

								?>
							</td>
							<td><?php echo $submission->getSize(); ?></td>
							<td class="comments">
								<?php echo nl2br(CHtml::encode($submission->comments)); ?>
							</td>
						</tr>
						<?php
						
						// Reset the row class and increment the submission counter
						$rowClasses = array();
						$totalSubmissionCount++;
					}
				}
				
				// Display a message if no submissions were found at all for the LAN
				if($totalSubmissionCount == 0) {
					?>
					<tr class="no-submissions">
						<td colspan="5">
							<?php echo Yii::t('submission', 'Finns inga submissions för detta LAN'); ?>
						</td>
					</tr>
					<?php
				}
				
				?>
			</table>
		</div>
		<?php
		
		echo ob_get_clean();
	}
	
}