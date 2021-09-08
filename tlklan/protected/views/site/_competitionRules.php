<?php

/* @var $this SiteController */
/* @var $competition Competition */

$purifier = new CHtmlPurifier();
$rules = $purifier->purify(nl2br($competition->rules));

if (empty($rules))
	$rules = '<i>'.Yii::t('competition', 'Finns inga skrivna regler för den här tävlingen just nu').'</i>';

?>
<div class="competition-rule">
	<h3>
		<i class="icon icon-exclamation-sign"></i> 
		<?php echo $competition->full_name; ?>
	</h3>
	
	<div class="rules">
		<?php echo $rules; ?>
	</div>
</div>