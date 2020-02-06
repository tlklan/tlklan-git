<?php

/* @var $dataProvider CActiveDataProvider */

?>
<div class="users">
	<h1><?php echo Yii::t('user', 'Användare'); ?></h1>
	
	<p>
		<?php echo Yii::t('user', 'Klicka på en användare för att se hans/hennes profil.'); ?>
	</p>
	
	<?php $this->widget('TbThumbnails', array(
		'dataProvider'=>$dataProvider,
		'template'=>'{items}',
		'itemView'=>'_listItem',
	)); ?>
</div>