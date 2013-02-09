<?php

/* @var $data User */
$profileUrl = Yii::app()->controller->createUrl('/user/profile', 
		array('id'=>$data->id));

?>
<li class="span2">
    <a href="<?php echo $profileUrl; ?>" class="thumbnail" rel="tooltip">
        <div class="image-container">
			<img src="<?php echo $data->getProfileImageUrl(); ?>" alt="">
		</div>
    </a>
	
	<?php echo $data->name; ?>
</li>