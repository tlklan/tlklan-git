<?php

$this->pageTitle = 'Arkiv';
$this->breadcrumbs=array(
	'Submissions'=>array('archive'),
	'Arkiv',
);

?>
<h1>Submissionarkiv</h1>
<?php  

$this->widget('cms.widgets.CmsBlock',array('name'=>'archive_info'));

foreach($lans as $lan) {
	$this->widget('application.widgets.submission.ArchiveListWidget', array(
		'lan'=>$lan,
	));
}