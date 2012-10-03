<?php

/**
 * Displays a set of submissions for a specific LAN
 *
 * @author Sam
 */
class ArchiveListWidget extends CWidget {
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
					<th>Tävling:</th>
					<th>Namn:</th>
					<th>Skapare:</th>
					<th>Storlek:</th>
					<th>Kommentar:</th>
				</tr>
				<?php
				
				$totalSubmissionCount = 0;
				
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
					
					$rowClass = 'submission-separator';
					
					// Start looping through each submission
					foreach($submissions as $k => $submission) {
						// Only show the competition name once
						$competitionName = ($k == 0) ? $competition->full_name : '';
						
						// Mark the winning submission(s) row, but only if there 
						// have actually been a vote
						if(max($submissionVotes) > 0 && $submission->voteCount 
								== max($submissionVotes)) 
						{
							$rowClass .= ' winner';
						}
						
						// Mark disqualified submissions
						if($submission->disqualified == true)
							$rowClass .= ' disqualified';
						
						?>
						<tr class="<?php echo $rowClass; ?>">
							<td><?php echo $competitionName; ?></td>
							<td>
								<?php 
								
								$submissionName = CHtml::encode($submission->name);
								
								// Show some buttons for logged in users
								if(Yii::app()->user->isGuest === false) {
									// download link
									echo CHtml::link(
										CHtml::image(Yii::app()->baseUrl.'/files/images/icons/save_icon_small.png'), 
										$this->controller->createUrl('/submission/get', array('id'=>$submission->id))
									);
										
									if(Yii::app()->user->isAdmin() === true) {
										// update link
										echo CHtml::link(
											CHtml::image(Yii::app()->baseUrl.'/files/images/icons/edit_button.png'), 
											$this->controller->createUrl('/submission/update', array('id'=>$submission->id))
										);
										
										// delete link
										echo CHtml::link(
											CHtml::image(Yii::app()->baseUrl.'/files/images/icons/delete_button.png'), 
											$this->controller->createUrl('/submission/delete', array('id'=>$submission->id)), 
											array(
												'confirm'=>"Är du säker?\n\nEntryn kommer endast att ta bort från databasen, inte får hårdskivan."
											)
										);
									}
									
									echo CHtml::link($submissionName, Yii::app()->controller->createUrl('/submission/get', array('id'=>$submission->id))); 
								}
								else
									echo $submissionName;
								
								?>
							</td>
							<td>
								<?php 

								// Some older submissions doesn't have a submitter
								if($submission->submitter !== null)
									echo $submission->submitter->nick; 

								?>
							</td>
							<td><?php echo $submission->size; ?></td>
							<td class="comments">
								<?php echo nl2br(CHtml::encode($submission->comments)); ?>
							</td>
						</tr>
						<?php
						
						// Reset the row class and increment the submission counter
						$rowClass = '';
						$totalSubmissionCount++;
					}
				}
				
				// Display a message if no submissions were found at all for the LAN
				if($totalSubmissionCount == 0) {
					?>
					<tr>
						<td colspan="5">Finns inga submissions för detta LAN</td>
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