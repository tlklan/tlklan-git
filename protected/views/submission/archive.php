<?php

$this->pageTitle = Yii::t('submission', 'Arkiv');
$this->breadcrumbs=array(
	Yii::t('submission', 'Submissions')=>array('archive'),
	Yii::t('submission', 'Arkiv'),
);

?>
<h1><?php echo Yii::t('submission', 'Submissionarkiv'); ?></h1>
<?php  

$this->widget('cms.widgets.CachedCmsBlock',array('name'=>'archive_info'));

foreach($lans as $lan)
	$this->widget('ArchiveList', array('lan'=>$lan));