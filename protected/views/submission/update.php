<?php

$this->pageTitle = 'Uppdatera submission';
$this->breadcrumbs=array(
	'Submissions'=>array('archive'),
	'Uppdatera submission',
);

?>
<h1>Uppdatera submission</h1>
<p>
	Fyll i fälten och klicka på "Uppdatera". Kontakta LAN-crew om din submission 
	är större än <b>64 MiB</b>!
</p>

<?php 

// Display the upload form
echo $this->renderPartial('_form', array(
	'model'=>$model,
	'competitions'=>$competitions,
));