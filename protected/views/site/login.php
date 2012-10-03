<?php

$this->pageTitle = 'Logga in';
$this->breadcrumbs=array(
	'Logga in',
);

?>
<h1>Logga in</h1>
<p>
	Fyll i användarnamn och lösenord för att logga in. Användaruppgifterna är 
	samma som till shellen.
</p>
<hr />
<?php 

/** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'verticalForm',
)); 

echo $form->textFieldRow($model, 'username', array('class'=>'span3 text-field-auto-focus'));
echo $form->passwordFieldRow($model, 'password', array('class'=>'span3'));

?>
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'icon'=>'ok white',
		'label'=>'Logga in'
	)); ?>
</div>
<?php $this->endWidget(); ?>